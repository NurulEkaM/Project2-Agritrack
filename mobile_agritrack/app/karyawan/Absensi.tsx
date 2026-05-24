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
  status: 'absen_datang' | 'absen_pulang' | 'lembur_datang';
  kegiatan: string | null; 
}

export default function AbsensiScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(true);
  const [absensiList, setAbsensiList] = useState<AbsensiItem[]>([]);
  const [stats, setStats] = useState({ hadir: 0, lembur: 0 });
  const [searchQuery, setSearchQuery] = useState<string>('');

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
          // Ganti dengan IP laptop Anda jika menggunakan HP fisik
          const API_URL = `http://10.0.2.2:8000/api/absensi?id_user=${idUser}`;
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
    if (screenName === 'Absensi') { 
        router.push('/karyawan/Absensi'); 
    } else if (screenName === 'Home') {
        router.push('/karyawan'); 
    } else if (screenName === 'Gaji') {
        router.push('/karyawan/gaji'); 
    } else if (screenName === 'Profile') {
        router.push('/karyawan/profile');
    }
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

  // LOGIKA SEARCHING (LOKASI & TANGGAL)
  const filteredData = absensiList.filter(item => {
    const query = searchQuery.toLowerCase().trim();
    if (!query) return true;

    const dateInfo = parseTimestamp(item.tanggal_datang);
    const dayName = dateInfo.day.toLowerCase();
    const dayNum = dateInfo.num.toLowerCase();

    return (
      item.lokasi.toLowerCase().includes(query) ||
      dayName.includes(query) ||
      dayNum.includes(query) ||
      item.tanggal_datang.includes(query) ||
      (item.kegiatan && item.kegiatan.toLowerCase().includes(query))
    );
  });

  const renderItem = ({ item }: { item: AbsensiItem }) => {
    const start = parseTimestamp(item.tanggal_datang);
    const end = item.tanggal_pulang ? parseTimestamp(item.tanggal_pulang) : null;
    const isOngoing = item.status !== 'absen_pulang';

    const Card = (
      <View style={styles.card}>
        {/* TANGGAL DINAMIS */}
        <View style={styles.cardDate}>
          <Text style={styles.dateText}>{start.day}</Text>
          <Text style={styles.dateNum}>{start.num}</Text>
        </View>

        {/* TRACKING TIMELINE */}
        <View style={styles.cardTimeline}>
          <View style={styles.timelineVisual}>
            <View style={styles.dotIn} />
            <View style={styles.line} />
            <View style={[styles.dotOut, !isOngoing && {backgroundColor: '#e67e22'}]} />
          </View>
          <View style={styles.timeContent}>
            <Text style={styles.timeLabel}>Masuk: <Text style={styles.timeValue}>{start.time}</Text></Text>
            <Text style={styles.locText}>
              <Ionicons name="location-outline" size={10}/> {item.lokasi.replace('_', ' ').toUpperCase()}
            </Text>
            <Text style={[styles.timeLabel, {marginTop: 15}]}>
              Pulang: <Text style={styles.timeValue}>{end?.time || '--:--'}</Text>
            </Text>
            {end && (
              <Text style={styles.durationText}>
                Durasi Kerja: {calculateDuration(item.tanggal_datang, item.tanggal_pulang!)}
              </Text>
            )}
            {item.kegiatan && (
              <Text style={[styles.timeLabel, {marginTop: 10}]}>
                Kegiatan: <Text style={styles.timeValue}>{item.kegiatan}</Text>
              </Text>
            )}
          </View>
        </View>

        {/* STATUS BADGE */}
        <View style={styles.cardBadge}>
          <View style={[styles.badge, isOngoing ? styles.badgeOn : styles.badgeOff]}>
            <Text style={[styles.badgeText, isOngoing ? styles.badgeTextOn : styles.badgeTextOff]}>
              {isOngoing ? 'Proses' : 'Selesai'}
            </Text>
          </View>
        </View>
      </View>
    );

    return isOngoing ? (
      <TouchableOpacity 
        activeOpacity={0.7}
        onPress={() => router.push({ pathname: '/karyawan/absen_pulang', params: { ...item } })}
      >
        {Card}
      </TouchableOpacity>
    ) : Card;
  };

  return (
    <SafeAreaView style={styles.container} edges={['right', 'left']}>
      <StatusBar barStyle="dark-content" />
      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={{padding: 20}}>
        
        {/* HEADER & STATS (Gambar 1) */}
        <Text style={styles.title}>Riwayat Absensi</Text>
        <Text style={styles.subtitle}>Pantau kehadiran kerja Anda setiap hari.</Text>

        <View style={styles.mainStats}>
           <View style={{flexDirection: 'row', justifyContent: 'space-between'}}>
              <Text style={styles.statsLabel}>Statistik Kehadiran</Text>
              <Ionicons name="document-text-outline" size={20} color="#117a65" />
           </View>
           <Text style={styles.statsBigNum}>{stats.hadir} <Text style={{fontSize: 14, fontWeight: 'normal'}}>Hari Hadir</Text></Text>
        </View>

        <View style={styles.rowStats}>
           <View style={[styles.subStats, {backgroundColor: '#fef5e7'}]}>
              <Text style={[styles.statsLabel, {color: '#b8860b'}]}><Ionicons name="time-outline"/> Lembur</Text>
              <Text style={styles.statsNum}>{stats.lembur} Hari</Text>
           </View>
           <View style={[styles.subStats, {backgroundColor: '#eaeded'}]}>
              <Text style={styles.statsLabel}><Ionicons name="calendar-outline"/> Bulan</Text>
              <Text style={styles.statsNum}>Mei</Text>
           </View>
        </View>

        {/* SEARCH BAR */}
        <View style={styles.searchContainer}>
          <Ionicons name="search-outline" size={18} color="#95a5a6" style={{marginRight: 10}} />
          <TextInput
            style={styles.searchInput}
            placeholder="Cari tanggal (contoh: 22) atau lokasi..."
            value={searchQuery}
            onChangeText={setSearchQuery}
            autoCapitalize="none"
          />
          {searchQuery.length > 0 && (
            <TouchableOpacity onPress={() => setSearchQuery('')}>
              <Ionicons name="close-circle" size={18} color="#bdc3c7" />
            </TouchableOpacity>
          )}
        </View>

        {/* LIST RIWAYAT (Gambar 2) */}
        {loading ? (
          <ActivityIndicator color="#117a65" style={{marginTop: 50}} />
        ) : (
          <FlatList
            data={filteredData}
            renderItem={renderItem}
            keyExtractor={item => item.id_absensi.toString()}
            scrollEnabled={false}
            ListEmptyComponent={
              <Text style={styles.emptyText}>Data tidak ditemukan.</Text>
            }
          />
        )}
        <View style={{height: 100}} />
      </ScrollView>
      
      {/* FLOATING ACTION BUTTON */}
      <TouchableOpacity style={styles.fab} onPress={() => router.push('/karyawan/input_absensi')}>
        <Ionicons name="add" size={30} color="white" />
      </TouchableOpacity>
      
      <BottomNav activeScreen="Absensi" onNavPress={handleNavigation} />

    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  title: { fontSize: 22, fontWeight: 'bold', color: '#2c3e50' },
  subtitle: { fontSize: 13, color: '#7f8c8d', marginBottom: 20 },
  mainStats: { backgroundColor: '#e8f8f5', borderRadius: 15, padding: 20, marginBottom: 15 },
  statsLabel: { fontSize: 12, color: '#117a65', fontWeight: 'bold' },
  statsBigNum: { fontSize: 32, fontWeight: 'bold', color: '#117a65', marginTop: 10 },
  rowStats: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 25 },
  subStats: { flex: 0.48, padding: 15, borderRadius: 15 },
  statsNum: { fontSize: 18, fontWeight: 'bold', color: '#2c3e50', marginTop: 5 },
  searchContainer: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#ffffff', borderRadius: 12, paddingHorizontal: 15, marginBottom: 20, borderWidth: 1, borderColor: '#eee', height: 45 },
  searchInput: { flex: 1, fontSize: 14, color: '#2c3e50' },
  card: { flexDirection: 'row', backgroundColor: '#fff', borderRadius: 15, padding: 15, marginBottom: 12, borderWidth: 1, borderColor: '#eee' },
  cardDate: { width: 50, alignItems: 'center', borderRightWidth: 1, borderRightColor: '#eee', justifyContent: 'center' },
  dateText: { fontSize: 10, color: '#7f8c8d', fontWeight: 'bold' },
  dateNum: { fontSize: 20, fontWeight: 'bold', color: '#2c3e50' },
  cardTimeline: { flex: 1, flexDirection: 'row', paddingLeft: 15 },
  timelineVisual: { alignItems: 'center', width: 20 },
  dotIn: { width: 10, height: 10, borderRadius: 5, backgroundColor: '#117a65' },
  line: { width: 1, height: 35, backgroundColor: '#eee', marginVertical: 2 },
  dotOut: { width: 10, height: 10, borderRadius: 5, backgroundColor: '#eee' },
  timeContent: { marginLeft: 10 },
  timeLabel: { fontSize: 12, color: '#7f8c8d' },
  timeValue: { color: '#2c3e50', fontWeight: 'bold' },
  locText: { fontSize: 10, color: '#bdc3c7', marginTop: 10 },
  durationText: { fontSize: 11, color: '#27ae60', fontWeight: 'bold', marginTop: 4 },
  cardBadge: { justifyContent: 'center' },
  badge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 10 },
  badgeOn: { backgroundColor: '#fff3e0' },
  badgeOff: { backgroundColor: '#e8f8f5' },
  badgeText: { fontSize: 10, fontWeight: 'bold' },
  badgeTextOn: { color: '#e67e22' },
  badgeTextOff: { color: '#117a65' },
  fab: { position: 'absolute', bottom: 100, right: 20, width: 56, height: 56, borderRadius: 28, backgroundColor: '#4ecb80', justifyContent: 'center', alignItems: 'center', elevation: 5 },
  emptyText: { textAlign: 'center', color: '#95a5a6', marginTop: 20 }
});