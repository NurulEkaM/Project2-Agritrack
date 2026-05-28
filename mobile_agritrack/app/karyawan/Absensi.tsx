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
  Platform,
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
  status: 'absen_datang' | 'absen_pulang' | 'tidak_hadir';
  kegiatan: string | null;
}

export default function AbsensiScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(true);
  const [absensiList, setAbsensiList] = useState<AbsensiItem[]>([]);
  const [stats, setStats] = useState({ hadir: 0, lembur: 0 });
  const [searchQuery, setSearchQuery] = useState<string>('');

  // Mendapatkan Nama Bulan Saat Ini secara Otomatis
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
          // Tetap gunakan 127.0.0.1 atau IP sesuai instruksi ledger Anda
          const API_URL = `http://10.0.2.2:8000/api/absensi?id_user=${idUser}`;
          // const API_URL = `http://127.0.0.1:8000/api/absensi?id_user=${idUser}`;
          // const API_URL = `http://10.231.171.66:8000/api/absensi?id_user=${idUser}`;
          const response = await fetch(API_URL);
          const data = await response.json();
          if (data.results) {
            setAbsensiList(data.results);
            const hadir = data.results.filter((item: any) =>
              item.status === 'absen_pulang' || item.status === 'absen_datang'
            ).length;
            const lembur = data.results.filter((item: any) => item.total_lembur > 0).length;
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

  const calculateDuration = (start: string, end: string) => {
    const startDate = new Date(start.replace(' ', 'T'));
    const endDate = new Date(end.replace(' ', 'T'));
    const diffMs = endDate.getTime() - startDate.getTime();
    const hrs = Math.floor(diffMs / 3600000);
    const mins = Math.round((diffMs % 3600000) / 60000);
    return `${hrs}j ${mins}m`;
  };

  const handleNavigation = (screenName: string) => {
    const routes: any = {
      'Absensi': '/karyawan/Absensi',
      'Home': '/karyawan',
      'Gaji': '/karyawan/gaji',
      'Profile': '/karyawan/profile',
      'Produk': '/karyawan/Produk'
    };
    if (routes[screenName]) router.push(routes[screenName]);
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

  const renderItem = ({ item }: { item: AbsensiItem }) => {
    const start = parseTimestamp(item.tanggal_datang);
    const end = item.tanggal_pulang ? parseTimestamp(item.tanggal_pulang) : null;
    const isOngoing = item.status !== 'absen_pulang' && item.status !== 'tidak_hadir';

    return (
      <TouchableOpacity
        activeOpacity={0.8}
        disabled={!isOngoing}
        onPress={() => router.push({ pathname: '/karyawan/absen_pulang', params: { ...item } })}
        style={styles.card}
      >
        <View style={styles.cardDateContainer}>
          <Text style={styles.cardDayText}>{start.day}</Text>
          <Text style={styles.cardNumText}>{start.num}</Text>
        </View>

        <View style={styles.cardContent} >
          <View style={styles.locationBadge}>
            <Ionicons name="location" size={10} color="#117a65" />
            <Text style={styles.locationText}>
              {item.status === 'absen_datang' || item.status === 'absen_pulang' ? item.lokasi.replace('_', ' ').toUpperCase() : 'TIDAK HADIR'}
            </Text>
          </View>

          <View style={styles.timeRow}>
            <View>
              <Text style={styles.timeLabel}>MASUK</Text>
              <Text style={styles.timeValue}>{start.time}</Text>
            </View>
            <Ionicons name="arrow-forward" size={16} color="#DDD" style={{ marginHorizontal: 10 }} />
            <View>
              <Text style={styles.timeLabel}>PULANG</Text>
              <Text style={styles.timeValue}>{end?.time || '--:--'}</Text>
            </View>
          </View>

          {item.tanggal_pulang && (
            <Text style={styles.durationTag}>
              <Ionicons name="timer-outline" size={12} /> {calculateDuration(item.tanggal_datang, item.tanggal_pulang)} kerja
            </Text>
          )}
        </View>

            <View style={[
              styles.statusIndicator, 
              item.status === 'tidak_hadir' ? styles.bgDanger : (isOngoing ? styles.bgProcess : styles.bgSuccess)
              ]}>
              <Text style={[
                  styles.statusLabel, 
                  item.status === 'tidak_hadir' ? styles.txtDanger : (isOngoing ? styles.txtProcess : styles.txtSuccess)
              ]}>
                  {item.status === 'tidak_hadir' ? 'TIDAK HADIR' : isOngoing ? 'PROSES' : 'SELESAI'}
              </Text>
            </View>
      </TouchableOpacity>
    );
  };

  return (
    <SafeAreaView style={styles.container} edges={['top']}>
      <StatusBar barStyle="dark-content" />
      
      <View style={styles.headerSection}>
        <Text style={styles.headerTitle}>Riwayat Absensi</Text>
        <Text style={styles.headerSubtitle}>Pantau produktivitas harian Anda</Text>
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={{ paddingHorizontal: 20 }}>
        
        {/* STATS SECTION */}
        <View style={styles.mainStatsCard}>
          <View style={styles.statsInfo}>
            <Text style={styles.statsTitle}>Kehadiran Anda</Text>
            <View style={styles.statsRowMain}>
              <Text style={styles.bigNumber}>{stats.hadir}</Text>
              <Text style={styles.bigNumberSub}>Hari Terdaftar</Text>
            </View>
          </View>
          <View style={styles.statsIconBg}>
            <MaterialCommunityIcons name="calendar-check" size={40} color="#FFF" />
          </View>
        </View>

        <View style={styles.smallStatsRow}>
          <View style={[styles.smallStatCard, { backgroundColor: '#FFF4E5' }]}>
            <View style={styles.smallStatIconBg}>
               <Ionicons name="flash" size={16} color="#FF9800" />
            </View>
            <View>
              <Text style={styles.smallStatLabel}>Lembur</Text>
              <Text style={styles.smallStatValue}>{stats.lembur} Hari</Text>
            </View>
          </View>

          <View style={[styles.smallStatCard, { backgroundColor: '#E8F5E9' }]}>
            <View style={[styles.smallStatIconBg, { backgroundColor: '#C8E6C9' }]}>
               <Ionicons name="calendar" size={16} color="#117a65" />
            </View>
            <View>
              <Text style={styles.smallStatLabel}>Bulan</Text>
              <Text style={[styles.smallStatValue, { color: '#117a65' }]}>{currentMonth}</Text>
            </View>
          </View>
        </View>

        {/* SEARCH SECTION */}
        <View style={styles.searchWrapper}>
          <Ionicons name="search" size={20} color="#BDBDBD" />
          <TextInput
            placeholder="Cari tanggal atau lokasi..."
            style={styles.searchField}
            value={searchQuery}
            onChangeText={setSearchQuery}
          />
          {searchQuery !== '' && (
            <TouchableOpacity onPress={() => setSearchQuery('')}>
              <Ionicons name="close-circle" size={20} color="#BDBDBD" />
            </TouchableOpacity>
          )}
        </View>

        {/* LIST SECTION */}
        {loading ? (
          <ActivityIndicator size="large" color="#117a65" style={{ marginTop: 40 }} />
        ) : (
          <FlatList
            data={filteredData}
            renderItem={renderItem}
            keyExtractor={item => item.id_absensi.toString()}
            scrollEnabled={false}
            ListEmptyComponent={
              <View style={styles.emptyContainer}>
                <Ionicons name="document-text-outline" size={50} color="#EEE" />
                <Text style={styles.emptyText}>Belum ada riwayat absensi</Text>
              </View>
            }
          />
        )}
        <View style={{ height: 120 }} />
      </ScrollView>

      {/* FAB */}
      <TouchableOpacity 
        style={styles.fab} 
        onPress={() => router.push('/karyawan/input_absensi')}
      >
        <Ionicons name="add" size={32} color="#FFF" />
      </TouchableOpacity>
      
      <BottomNav activeScreen="Absensi" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  headerSection: { paddingHorizontal: 20, paddingVertical: 15 },
  headerTitle: { fontSize: 26, fontWeight: '800', color: '#1A252F' },
  headerSubtitle: { fontSize: 14, color: '#95A5A6', marginTop: 2 },
  
  mainStatsCard: {
    backgroundColor: '#117a65',
    borderRadius: 24,
    padding: 24,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    elevation: 8,
    shadowColor: '#117a65',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 10,
    marginBottom: 16
  },
  statsInfo: { flex: 1 },
  statsTitle: { color: '#A5D6A7', fontSize: 14, fontWeight: '600' },
  statsRowMain: { flexDirection: 'row', alignItems: 'baseline', marginTop: 8 },
  bigNumber: { color: '#FFFFFF', fontSize: 38, fontWeight: 'bold' },
  bigNumberSub: { color: '#FFFFFF', fontSize: 14, marginLeft: 8, opacity: 0.8 },
  statsIconBg: { backgroundColor: 'rgba(255,255,255,0.2)', padding: 12, borderRadius: 20 },

  smallStatsRow: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 24 },
  smallStatCard: { flex: 0.48, borderRadius: 20, padding: 15, flexDirection: 'row', alignItems: 'center' },
  smallStatIconBg: { backgroundColor: '#FFECB3', padding: 8, borderRadius: 12, marginRight: 12 },
  smallStatLabel: { fontSize: 11, color: '#7F8C8D', fontWeight: '600' },
  smallStatValue: { fontSize: 15, fontWeight: 'bold', color: '#E67E22' },

  searchWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F8F9FA',
    borderRadius: 16,
    paddingHorizontal: 16,
    height: 50,
    borderWidth: 1,
    borderColor: '#F1F1F1',
    marginBottom: 20
  },
  searchField: { flex: 1, marginLeft: 10, fontSize: 14, color: '#2C3E50' },

  card: {
    flexDirection: 'row',
    backgroundColor: '#FFFFFF',
    borderRadius: 20,
    padding: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    alignItems: 'center',
    ...Platform.select({
      ios: { shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.05, shadowRadius: 5 },
      android: { elevation: 2 }
    })
  },
  cardDateContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingRight: 16,
    borderRightWidth: 1,
    borderRightColor: '#F5F5F5',
    width: 60
  },
  cardDayText: { fontSize: 11, color: '#9E9E9E', fontWeight: 'bold' },
  cardNumText: { fontSize: 22, fontWeight: '800', color: '#2C3E50' },
  cardContent: { flex: 1, paddingLeft: 16 },
  locationBadge: { 
    flexDirection: 'row', 
    alignItems: 'center', 
    backgroundColor: '#E8F5E9', 
    alignSelf: 'flex-start', 
    paddingHorizontal: 8, 
    paddingVertical: 2, 
    borderRadius: 6,
    marginBottom: 8
  },
  locationText: { fontSize: 9, color: '#117a65', fontWeight: 'bold', marginLeft: 4 },
  timeRow: { flexDirection: 'row', alignItems: 'center' },
  timeLabel: { fontSize: 9, color: '#BDC3C7', fontWeight: 'bold', letterSpacing: 0.5 },
  timeValue: { fontSize: 16, fontWeight: '700', color: '#2C3E50' },
  durationTag: { fontSize: 11, color: '#117a65', fontWeight: '600', marginTop: 6 },
  
  statusIndicator: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 10 },
  bgProcess: { backgroundColor: '#FFF3E0' },
  bgSuccess: { backgroundColor: '#E8F5E9' },
  bgDanger: { backgroundColor: '#FFEBEE' },
  txtDanger: { color: '#E53935' },
  statusLabel: { fontSize: 10, fontWeight: '800' },
  txtProcess: { color: '#E67E22' },
  txtSuccess: { color: '#117a65' },

  fab: {
    position: 'absolute',
    bottom: 100,
    right: 20,
    width: 64,
    height: 64,
    borderRadius: 32,
    backgroundColor: '#117a65',
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 8,
    shadowColor: '#117a65',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.4,
    shadowRadius: 8
  },
  emptyContainer: { alignItems: 'center', marginTop: 60 },
  emptyText: { color: '#BDC3C7', marginTop: 10, fontSize: 14 }
});