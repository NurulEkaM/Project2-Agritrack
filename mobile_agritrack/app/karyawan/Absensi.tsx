import React, { useState, useEffect } from 'react';
import {
  Text,
  View,
  ScrollView,
  TouchableOpacity,
  StatusBar,
  ActivityIndicator,
  FlatList,
  StyleSheet,
  TextInput
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import BottomNav from './components/BottomNav';

interface AbsensiItem {
  id_absensi: number;
  tanggal_datang: string;
  tanggal_pulang: string | null;
  lokasi: 'kebun_lanud' | 'kebun_sadang';
  status: 'absen_datang' | 'absen_pulang' | 'tidak_hadir' | 'lembur' | 'selesai';
  kegiatan: string | null;
  total_lembur?: number;
}

export default function AbsensiScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(true);
  const [absensiList, setAbsensiList] = useState<AbsensiItem[]>([]);
  const [stats, setStats] = useState({ hadir: 0, lembur: 0 });
  const [searchQuery, setSearchQuery] = useState<string>('');

  const currentMonth = new Date().toLocaleString('id-ID', { month: 'long' });

  useEffect(() => {
    fetchAbsensiData();
  }, []);

  const fetchAbsensiData = async () => {
    setLoading(true);
    try {
      const jsonValue = await AsyncStorage.getItem('user_session');
      if (jsonValue != null) {
        const sessionData = JSON.parse(jsonValue);
        const idUser = sessionData.user?.id_user;
        if (idUser) {
          const API_URL = `http://10.0.2.2:8000/api/absensi?id_user=${idUser}`;
          const response = await fetch(API_URL);
          const data = await response.json();
          if (data.results) {
            setAbsensiList(data.results);
            const hadir = data.results.filter((item: any) =>
              ['absen_pulang', 'absen_datang', 'lembur', 'selesai'].includes(item.status)
            ).length;
            const lembur = data.results.filter((item: any) => 
              ['lembur', 'selesai'].includes(item.status) || (item.total_lembur > 0)
            ).length;
            setStats({ hadir, lembur });
          }
        }
      }
    } catch (error) {
      console.error("Fetch Error:", error);
    } finally {
      setLoading(false);
    }
  };

  const isToday = (dateString: string) => {
    const date = new Date(dateString.replace(' ', 'T'));
    const today = new Date();
    return (
      date.getDate() === today.getDate() &&
      date.getMonth() === today.getMonth() &&
      date.getFullYear() === today.getFullYear()
    );
  };

  const calculateDuration = (start: string, end: string) => {
    const startDate = new Date(start.replace(' ', 'T'));
    const endDate = new Date(end.replace(' ', 'T'));
    const diffMs = endDate.getTime() - startDate.getTime();
    const hrs = Math.floor(diffMs / 3600000);
    const mins = Math.round((diffMs % 3600000) / 60000);
    return `${hrs}j ${mins}m`;
  };

  const parseTimestamp = (ts: string) => {
    const date = new Date(ts.replace(' ', 'T'));
    const days = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
    return {
      day: days[date.getDay()],
      num: date.getDate().toString().padStart(2, '0'),
      time: ts.substring(11, 16)
    };
  };

  const filteredData = absensiList.filter(item => {
    const query = searchQuery.toLowerCase().trim();
    if (!query) return true;
    return (
      item.lokasi.toLowerCase().includes(query) || 
      item.tanggal_datang.includes(query) ||
      (item.kegiatan && item.kegiatan.toLowerCase().includes(query))
    );
  });

  const handleNavigation = (screenName: string) => {
    const routes: any = { 'Absensi': '/karyawan/Absensi', 'Home': '/karyawan', 'Gaji': '/karyawan/gaji', 'Profile': '/karyawan/profile', 'Produk': '/karyawan/Produk' };
    if (routes[screenName]) router.push(routes[screenName]);
  };

  const renderItem = ({ item }: { item: AbsensiItem }) => {
    const start = parseTimestamp(item.tanggal_datang);
    const end = item.tanggal_pulang ? parseTimestamp(item.tanggal_pulang) : null;
    const isTodayDate = isToday(item.tanggal_datang);
    const isTidakHadir = item.status === 'tidak_hadir';

    if (!isTodayDate) {
      return (
        <View style={styles.cardContainer}>
          <View style={[styles.cardHeaderMenu, { borderBottomWidth: 0, paddingBottom: 0 }]}>
            <View style={styles.cardHeaderTitleRow}>
              <MaterialCommunityIcons name="calendar" size={16} color="#757575" />
              <Text style={styles.cardHeaderTitleText}>{start.day}, {start.num} {currentMonth}</Text>
            </View>
            <Text style={[styles.locationText, isTidakHadir && styles.txtDanger]}>
              {isTidakHadir ? 'TIDAK HADIR' : item.lokasi.replace('_', ' ').toUpperCase()}
            </Text>
          </View>
          <View style={{ flexDirection: 'row', paddingHorizontal: 12, paddingVertical: 10, justifyContent: 'space-between', borderTopWidth: 0.5, borderTopColor: '#F5F5F5', marginTop: 8 }}>
            <Text style={{ fontSize: 11, color: '#424242' }}>Masuk: <Text style={{ fontWeight: 'bold' }}>{isTidakHadir ? '-' : start.time}</Text></Text>
            <Text style={{ fontSize: 11, color: '#424242' }}>Pulang: <Text style={{ fontWeight: 'bold' }}>{end ? end.time : '-'}</Text></Text>
            <Text style={{ fontSize: 11, color: '#117a65', fontWeight: 'bold' }}>{isTidakHadir ? 'ALFA' : item.status.toUpperCase()}</Text>
          </View>
        </View>
      );
    }

    const isOngoing = item.status !== 'selesai' && item.status !== 'absen_pulang' && !isTidakHadir;
    const stepMasuk = !isTidakHadir;
    const stepPulang = ['absen_pulang', 'lembur', 'selesai'].includes(item.status) || !!item.tanggal_pulang;
    const stepLembur = ['lembur', 'selesai'].includes(item.status) && item.total_lembur !== undefined && item.total_lembur > 0;
    const stepSelesai = ['absen_pulang', 'selesai'].includes(item.status);

    return (
      <TouchableOpacity activeOpacity={0.85} disabled={!isOngoing} onPress={() => router.push({ pathname: '/karyawan/absen_pulang', params: { ...item } })} style={styles.cardContainer}>
        <View style={styles.cardHeaderMenu}>
          <View style={styles.cardHeaderTitleRow}>
            <MaterialCommunityIcons name="calendar-clock" size={18} color="#117a65" />
            <Text style={styles.cardHeaderTitleText}>{start.day}, {start.num} {currentMonth}</Text>
          </View>
          <View style={styles.cardHeaderRightRow}>
            <Text style={[styles.locationText, isTidakHadir && styles.txtDanger]}>{isTidakHadir ? 'TIDAK HADIR' : item.lokasi.replace('_', ' ').toUpperCase()}</Text>
            {isOngoing && <Ionicons name="chevron-forward" size={14} color="#FF9800" />}
          </View>
        </View>
        <View style={styles.trackingGrid}>
          <View style={styles.gridItem}>
            <View style={styles.iconWrapper}><MaterialCommunityIcons name="login-variant" size={22} color={stepMasuk ? '#117a65' : '#9E9E9E'} /></View>
            <Text style={styles.gridLabel}>Masuk</Text>
            <Text style={[styles.gridSubText, stepMasuk && styles.txtActiveStatus]}>{isTidakHadir ? '--:--' : start.time}</Text>
          </View>
          <View style={styles.gridItem}>
            <View style={styles.iconWrapper}><MaterialCommunityIcons name="logout-variant" size={22} color={stepPulang ? '#117a65' : '#9E9E9E'} /></View>
            <Text style={styles.gridLabel}>Pulang</Text>
            <Text style={[styles.gridSubText, stepPulang && styles.txtActiveStatus]}>{end?.time || '--:--'}</Text>
          </View>
          <View style={styles.gridItem}>
            <View style={styles.iconWrapper}><MaterialCommunityIcons name="clock-fast" size={22} color={stepLembur ? '#FF9800' : '#9E9E9E'} /></View>
            <Text style={styles.gridLabel}>Lembur</Text>
            <Text style={[styles.gridSubText, stepLembur && styles.txtOrangeStatus]}>{stepLembur ? `${item.total_lembur} Jam` : '-'}</Text>
          </View>
          <View style={styles.gridItem}>
            <View style={styles.iconWrapper}><MaterialCommunityIcons name={isTidakHadir ? "close-circle-outline" : "checkbox-marked-circle-outline"} size={22} color={stepSelesai ? '#4CAF50' : (isTidakHadir ? '#D32F2F' : '#9E9E9E')} /></View>
            <Text style={styles.gridLabel}>Status</Text>
            <Text style={[styles.gridSubText, stepSelesai && styles.txtSuccessStatus, isTidakHadir && styles.txtDangerStatus, isOngoing && styles.txtProcessStatus]}>{isTidakHadir ? 'ALFA' : stepSelesai ? 'SELESAI' : item.status.toUpperCase()}</Text>
          </View>
        </View>
        {item.tanggal_pulang && (
          <View style={styles.cardFooterInfo}><Text style={styles.footerDurationText}>Durasi Kerja: {calculateDuration(item.tanggal_datang, item.tanggal_pulang)}</Text></View>
        )}
      </TouchableOpacity>
    );
  };

  return (
    <SafeAreaView style={styles.container} edges={['top']}>
      <StatusBar barStyle="light-content" backgroundColor="#117a65" />
      <View style={styles.shopeeHeaderBackground}>
        <View style={styles.shopeeTopActions}>
          <View style={styles.myShopBadge}><Text style={styles.myShopText}>Karyawan App</Text></View>
          <View style={styles.iconHeaderRightRow}>
            <Ionicons name="settings-outline" size={20} color="#FFF" style={{ marginRight: 16 }} />
            <Ionicons name="chatbubble-ellipses-outline" size={20} color="#FFF" />
          </View>
        </View>
        <View style={styles.titlePageContainer}>
          <Text style={styles.headerTitleText}>Riwayat Absensi</Text>
          <Text style={styles.headerSubtitleText}>Pantau kelengkapan jam dan tracking kerja Anda</Text>
        </View>
      </View>

      <ScrollView showsVerticalScrollIndicator={false} style={styles.scrollBodyContainer}>
        <View style={styles.walletSectionContainer}>
          <View style={styles.walletHeaderRow}>
            <View style={{ flexDirection: 'row', alignItems: 'center' }}>
              <MaterialCommunityIcons name="wallet" size={18} color="#117a65" style={{ marginRight: 6 }} />
              <Text style={styles.walletTitleText}>Ringkasan Absensi ({currentMonth})</Text>
            </View>
          </View>
          <View style={styles.walletGridRow}>
            <View style={styles.walletGridItem}><Text style={styles.walletValueText}>{stats.hadir}</Text><Text style={styles.walletLabelText}>Hadir (Hari)</Text></View>
            <View style={styles.walletGridItem}><Text style={styles.walletValueText}>{stats.lembur}</Text><Text style={styles.walletLabelText}>Lembur (Hari)</Text></View>
          </View>
        </View>

        <View style={styles.searchWrapper}>
          <Ionicons name="search" size={18} color="#BDBDBD" />
          <TextInput placeholder="Cari tanggal atau lokasi kebun..." style={styles.searchField} value={searchQuery} onChangeText={setSearchQuery} />
          {searchQuery !== '' && <TouchableOpacity onPress={() => setSearchQuery('')}><Ionicons name="close-circle" size={18} color="#BDBDBD" /></TouchableOpacity>}
        </View>

        {loading ? (
          <ActivityIndicator size="large" color="#117a65" style={{ marginTop: 40 }} />
        ) : (
          <FlatList data={filteredData} renderItem={renderItem} keyExtractor={item => item.id_absensi.toString()} scrollEnabled={false} />
        )}
        <View style={{ height: 120 }} />
      </ScrollView>

      <TouchableOpacity style={styles.fab} onPress={() => router.push('/karyawan/Absensi_masuk')}>
        <Ionicons name="add" size={28} color="#FFF" />
      </TouchableOpacity>
      <BottomNav activeScreen="Absensi" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F5F5F5' },
  shopeeHeaderBackground: { backgroundColor: '#117a65', paddingHorizontal: 16, paddingTop: 12, paddingBottom: 28 },
  shopeeTopActions: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 },
  myShopBadge: { backgroundColor: 'rgba(255,255,255,0.2)', paddingHorizontal: 12, paddingVertical: 4, borderRadius: 12 },
  myShopText: { color: '#FFF', fontSize: 11, fontWeight: 'bold' },
  iconHeaderRightRow: { flexDirection: 'row', alignItems: 'center' },
  titlePageContainer: { marginTop: 4, paddingHorizontal: 2 },
  headerTitleText: { color: '#FFF', fontSize: 22, fontWeight: '800' },
  headerSubtitleText: { color: '#FFEB3B', fontSize: 12, marginTop: 4, opacity: 0.9 },
  scrollBodyContainer: { flex: 1, marginTop: -14, paddingHorizontal: 12 },
  walletSectionContainer: { backgroundColor: '#FFF', borderRadius: 8, padding: 12, marginBottom: 12, elevation: 2 },
  walletHeaderRow: { flexDirection: 'row', borderBottomWidth: 0.5, borderBottomColor: '#F0F0F0', paddingBottom: 8, marginBottom: 10 },
  walletTitleText: { fontSize: 12, fontWeight: '700' },
  walletGridRow: { flexDirection: 'row', justifyContent: 'space-around' },
  walletGridItem: { alignItems: 'center', flex: 1 },
  walletValueText: { fontSize: 16, fontWeight: 'bold', color: '#117a65' },
  walletLabelText: { fontSize: 11, color: '#757575' },
  searchWrapper: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#FFF', borderRadius: 8, paddingHorizontal: 12, height: 42, marginBottom: 12, borderWidth: 0.5, borderColor: '#E0E0E0' },
  searchField: { flex: 1, marginLeft: 8, fontSize: 13 },
  cardContainer: { backgroundColor: '#FFF', borderRadius: 8, marginBottom: 12, paddingVertical: 12, elevation: 1 },
  cardHeaderMenu: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 12, borderBottomWidth: 0.5, borderBottomColor: '#F5F5F5', paddingBottom: 8 },
  cardHeaderTitleRow: { flexDirection: 'row', alignItems: 'center' },
  cardHeaderTitleText: { fontSize: 13, fontWeight: '700', marginLeft: 6 },
  cardHeaderRightRow: { flexDirection: 'row', alignItems: 'center' },
  locationText: { fontSize: 11, color: '#117a65', fontWeight: '700', marginRight: 4 },
  trackingGrid: { flexDirection: 'row', justifyContent: 'space-between', paddingTop: 12, paddingHorizontal: 6 },
  gridItem: { alignItems: 'center', flex: 1 },
  iconWrapper: { position: 'relative', marginBottom: 6 },
  gridLabel: { fontSize: 11, color: '#212121' },
  gridSubText: { fontSize: 11, color: '#757575', fontWeight: '700', marginTop: 2 },
  txtActiveStatus: { color: '#117a65' },
  txtOrangeStatus: { color: '#FF9800' },
  txtSuccessStatus: { color: '#4CAF50' },
  txtDangerStatus: { color: '#D32F2F' },
  txtProcessStatus: { color: '#FF9800' },
  txtDanger: { color: '#D32F2F' },
  cardFooterInfo: { marginTop: 10, borderTopWidth: 0.5, borderTopColor: '#F5F5F5', paddingTop: 6, paddingHorizontal: 12, alignItems: 'flex-end' },
  footerDurationText: { fontSize: 10, color: '#757575', fontStyle: 'italic' },
  fab: { position: 'absolute', bottom: 90, right: 16, width: 54, height: 54, borderRadius: 27, backgroundColor: '#117a65', justifyContent: 'center', alignItems: 'center', elevation: 4 }
});