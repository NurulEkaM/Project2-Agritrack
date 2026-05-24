import React, { useState, useEffect } from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  TouchableOpacity, 
  ScrollView, 
  ActivityIndicator, 
  SafeAreaView 
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import BottomNav from './components/BottomNav'; // Mengimpor BottomNav milik Anda

// Interface data dari API Laravel
interface GajiData {
  id_gaji: number;
  tanggal: string; // format: YYYY-MM-DD
  total_gaji: number;
  total_lembur: number;
  keterangan: string; // 'Normal', 'Insentif', 'Potongan'
  status_hadir?: string;
}

export default function RiwayatGaji() {
  const router = useRouter();
  const [riwayatGaji, setRiwayatGaji] = useState<GajiData[]>([]);
  const [filter, setFilter] = useState<'semua' | 'normal' | 'lembur'>('semua');
  const [loading, setLoading] = useState<boolean>(true);
  const [userId, setUserId] = useState<string | null>(null);

  useEffect(() => {
    fetchGajiData();
  }, []);

  // Mengambil sesi user dan fetch data gaji berdasarkan id_user
  const fetchGajiData = async () => {
  setLoading(true);
  try {
    const jsonValue = await AsyncStorage.getItem('user_session');
    if (jsonValue != null) {
      const sessionData = JSON.parse(jsonValue);
      const idUser = sessionData.user?.id_user; 
      setUserId(idUser);

      if (idUser) {
        // PERHATIKAN: Menggunakan /${idUser} di ujung URL
        const API_URL = `http://10.0.2.2:8000/api/gaji/${idUser}`;
        
        const response = await fetch(API_URL);
        const data = await response.json();

        if (data.results) {
          setRiwayatGaji(data.results);
        }
      }
    }
  } catch (error) {
    console.error("Gagal memuat data gaji:", error);
  } finally {
    setLoading(false);
  }
};

  // Hitung ringkasan statistik harian berdasarkan data user yang login
  const totalHariHadir = riwayatGaji.length;
  const totalJamLembur = riwayatGaji.reduce((acc, curr) => acc + curr.total_lembur, 0);

  // Filter list data berdasarkan tab yang aktif
  const dataTersaring = riwayatGaji.filter((item) => {
    if (filter === 'normal') return item.total_lembur === 0;
    if (filter === 'lembur') return item.total_lembur > 0;
    return true;
  });

  // Helper format mata uang rupiah
  const formatRupiah = (angka: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(angka);
  };

  // Helper memisahkan nama hari dan tanggal (Misal: "12" & "Sen")
  const formatTanggalDaftar = (dateString: string) => {
    const date = new Date(dateString);
    const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const hari = days[date.getDay()];
    const tgl = date.getDate();
    return { hari, tgl };
  };

  // Logika navigasi navbar bawah disamakan dengan file Absensi Anda
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

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#117a65" />
        <Text style={styles.loadingText}>Memuat Riwayat Gaji...</Text>
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        
        {/* --- HEADER & RINGKASAN --- */}
        <View style={styles.headerSection}>
          <Text style={styles.titleHeader}>Riwayat Gaji</Text>
          
          <View style={styles.subHeaderContainer}>
            <Text style={styles.subTitle}>Riwayat Gaji</Text>
            <Text style={styles.description}>Pantau kehadiran kerja Anda setiap hari.</Text>
          </View>

          {/* Kotak Angka Ringkasan */}
          <View style={styles.summaryContainer}>
            <View style={styles.summaryBox}>
              <Text style={styles.summaryNumberColor}>{totalHariHadir}</Text>
              <Text style={styles.summaryLabel}>Kali Gaji</Text>
            </View>
            <View style={[styles.summaryBox, styles.summaryBorderLeft]}>
              <Text style={styles.summaryNumberDark}>{totalJamLembur}</Text>
              <Text style={styles.summaryLabel}>Total Jam Lembur</Text>
            </View>
          </View>

          {/* --- TABS / FILTER BUTTONS --- */}
          <View style={styles.filterContainer}>
            {/* Menggunakan perulangan array untuk kebersihan kode */}
            {(['semua', 'normal', 'lembur'] as const).map((tab) => (
              <TouchableOpacity 
                key={tab}
                style={[styles.filterButton, filter === tab && styles.filterActiveButton]}
                onPress={() => setFilter(tab)}
              >
                <Text style={[styles.filterText, filter === tab && styles.filterActiveText]}>
                  {tab.charAt(0).toUpperCase() + tab.slice(1)}
                </Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>

        {/* --- DAFTAR RIWAYAT HARIAN --- */}
        <View style={styles.listSection}>
          <View style={styles.listTitleContainer}>
            <Ionicons name="calendar-outline" size={16} color="#117a65" />
            <Text style={styles.listTitleText}>Riwayat Kehadiran Harian</Text>
          </View>

          {dataTersaring.map((item) => {
            const { hari, tgl } = formatTanggalDaftar(item.tanggal);
            
            // Logika Warna dinamis berdasarkan keterangan status
            let borderSideColor = '#117a65'; 
            let tagColor = '#117a65';
            if (item.keterangan.toLowerCase() === 'insentif') {
              borderSideColor = '#f39c12'; 
              tagColor = '#f39c12';
            } else if (item.keterangan.toLowerCase() === 'potongan') {
              borderSideColor = '#e74c3c'; 
              tagColor = '#e74c3c';
            }

            return (
              <TouchableOpacity
                key={item.id_gaji}
                activeOpacity={0.7}
                onPress={() => router.push({
                pathname: '/karyawan/detail_gaji',
                params: { id_gaji: item.id_gaji }
                })}
                style={[styles.cardItem, { borderLeftColor: borderSideColor }]}
              >
                {/* Sisi Kiri: Tanggal & Info Jam */}
                <View style={styles.cardLeft}>
                  {/* Kotak Kalender Tanggal */}
                  <View style={styles.dateBadge}>
                    <Text style={styles.dateDayText}>{hari}</Text>
                    <Text style={styles.dateNumberText}>{tgl < 10 ? `0${tgl}` : tgl}</Text>
                  </View>
                  
                  {/* Deskripsi Kehadiran */}
                  <View style={styles.infoKehadiran}>
                    <Text style={styles.statusHadirText}>
                      {item.status_hadir || (item.total_lembur > 0 ? `Hadir + Lembur ${item.total_lembur}j` : 'Hadir')}
                    </Text>
                    <Text style={[styles.jamKerjaText, item.keterangan.toLowerCase() === 'potongan' && { color: '#e74c3c' }]}>
                      🕒 Gaji PerMinggu
                    </Text>
                  </View>
                </View>

                {/* Sisi Kanan: Uang & Keterangan Tag */}
                <View style={styles.cardRight}>
                  <Text style={styles.nominalText}>{formatRupiah(item.total_gaji)}</Text>
                  <Text style={[styles.tagKeterangan, { color: tagColor }]}>{item.keterangan}</Text>
                </View>
              </TouchableOpacity>
            );
          })}

          {dataTersaring.length === 0 && (
            <Text style={styles.emptyText}>Tidak ada riwayat untuk kategori ini.</Text>
          )}
        </View>

      </ScrollView>

      {/* --- BOTTOM NAV BAR --- */}
      <BottomNav activeScreen="Gaji" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
  },
  scrollContent: {
    paddingBottom: 90,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
  },
  loadingText: {
    marginTop: 10,
    fontSize: 13,
    color: '#7f8c8d',
  },
  headerSection: {
    backgroundColor: '#ffffff',
    paddingHorizontal: 20,
    paddingTop: 20,
    paddingBottom: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f2f6',
  },
  titleHeader: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#117a65',
  },
  subHeaderContainer: {
    marginTop: 15,
  },
  subTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#2c3e50',
  },
  description: {
    fontSize: 11,
    color: '#95a5a6',
    marginTop: 2,
  },
  summaryContainer: {
    flexDirection: 'row',
    marginTop: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f2f6',
    paddingBottom: 15,
  },
  summaryBox: {
    flex: 1,
  },
  summaryBorderLeft: {
    borderLeftWidth: 1,
    borderLeftColor: '#e2e8f0',
    paddingLeft: 25,
  },
  summaryNumberColor: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#27ae60',
  },
  summaryNumberDark: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  summaryLabel: {
    fontSize: 11,
    color: '#7f8c8d',
    fontWeight: '500',
    marginTop: 2,
  },
  filterContainer: {
    flexDirection: 'row',
    marginTop: 15,
  },
  filterButton: {
    paddingHorizontal: 18,
    paddingVertical: 7,
    borderRadius: 20,
    backgroundColor: '#f1f2f6',
    marginRight: 8,
  },
  filterActiveButton: {
    backgroundColor: '#0e6251',
  },
  filterText: {
    fontSize: 12,
    color: '#7f8c8d',
    fontWeight: '600',
  },
  filterActiveText: {
    color: '#ffffff',
  },
  listSection: {
    padding: 16,
  },
  listTitleContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
  },
  listTitleText: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginLeft: 6,
  },
  cardItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    padding: 12,
    borderRadius: 12,
    borderLeftWidth: 4,
    marginBottom: 10,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 3,
  },
  cardLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  dateBadge: {
    backgroundColor: '#f1f2f6',
    borderRadius: 8,
    width: 44,
    height: 44,
    justifyContent: 'center',
    alignItems: 'center',
  },
  dateDayText: {
    fontSize: 10,
    color: '#95a5a6',
    fontWeight: '500',
  },
  dateNumberText: {
    fontSize: 15,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginTop: 1,
  },
  infoKehadiran: {
    marginLeft: 12,
  },
  statusHadirText: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  jamKerjaText: {
    fontSize: 10,
    color: '#95a5a6',
    marginTop: 3,
    fontWeight: '500',
  },
  cardRight: {
    alignItems: 'flex-end',
  },
  nominalText: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  tagKeterangan: {
    fontSize: 10,
    fontWeight: 'bold',
    marginTop: 2,
    textTransform: 'capitalize',
  },
  emptyText: {
    textAlign: 'center',
    color: '#95a5a6',
    fontSize: 12,
    marginTop: 20,
  }
});