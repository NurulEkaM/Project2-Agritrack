import React, { useState, useEffect } from 'react';
import {
  StyleSheet,
  Text,
  View,
  ScrollView,
  SafeAreaView,
  ActivityIndicator,
  RefreshControl,
  TouchableOpacity,
  TextInput,
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import BottomNav from './components/BottomNav';

interface Kredit {
  id_kredit: number;
  nama: string;
  tanggal: string;
  jenis_pengeluaran: string;
  saldo_kredit: number;
  status: 'setuju' | 'tidak disetuju' | 'tunggu';
  keterangan: string;
}

const PengeluaranScreen = () => {
  const router = useRouter();
  const [dataKredit, setDataKredit] = useState<Kredit[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState(''); // State untuk pencarian

  const API_URL = 'http://10.0.2.2:8000/api/kredit';

  const fetchDataKredit = async () => {
    try {
      const response = await fetch(API_URL);
      const json = await response.json();
      const sortedData = json.results.sort((a: Kredit, b: Kredit) => b.id_kredit - a.id_kredit);
      setDataKredit(sortedData);
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchDataKredit();
  }, []);

  const onRefresh = () => {
    setRefreshing(true);
    fetchDataKredit();
  };

  const formatRupiah = (number: number) => {
    return new Intl.NumberFormat('id-ID').format(number);
  };

  // Logika Filter
  const filteredData = dataKredit.filter((item) =>
    item.nama.toLowerCase().includes(searchQuery.toLowerCase()) ||
    item.keterangan.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const handleNavigation = (screenName: string) => {
    if (screenName === 'Home') router.push('/owner');
    else if (screenName === 'Pengeluaran') router.push('/owner/pengeluaran');
    else if (screenName === 'Karyawan') router.push('/owner/karyawan');
    else if (screenName === 'Laporan') router.push('/owner/laporan');
    else if (screenName === 'Profile') router.push('/owner/profile');
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView 
        contentContainerStyle={styles.scrollContent}
        keyboardShouldPersistTaps="handled"
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#117a65" />
        }
      >
        <View style={styles.header}>
          <Text style={styles.headerTitle}>Pengeluaran</Text>
          <Text style={styles.subHeaderTitle}>Pengeluaran (Kredit)</Text>
          <Text style={styles.headerDescription}>Manage and track your financial health.</Text>
        </View>

        <View style={styles.filterRow}>
          <View style={styles.filterCard}>
            <Text style={styles.filterLabel}>JLM SEMUA PENGELUARAN</Text>
            <View style={styles.filterValueContainer}>
              <Text style={styles.filterValue}>
                {formatRupiah(filteredData.filter(item => item.status === 'setuju').length)}
              </Text>
              <Ionicons name="checkmark-circle" size={18} color="#555" />
            </View>
          </View>
          <View style={styles.filterCard}>
            <Text style={styles.filterLabel}>JUMLAH UANG (SETUJU)</Text>
            <View style={styles.filterValueContainer}>
              <Text style={[styles.filterValue, { fontSize: 12 }]}>
                Rp {formatRupiah(filteredData.filter(item => item.status === 'setuju').reduce((sum, item) => sum + item.saldo_kredit, 0))}
              </Text>
              <MaterialCommunityIcons name="cash-check" size={18} color="#117a65" />
            </View>
          </View>
        </View>

                {/* Input Pencarian */}
        <View style={styles.searchContainer}>
          <Ionicons name="search" size={20} color="#7f8c8d" />
          <TextInput
            style={styles.searchInput}
            placeholder="Cari berdasarkan nama "
            value={searchQuery}
            onChangeText={setSearchQuery}
          />
        </View>

        <View style={styles.listContainer}>
          {loading ? (
            <ActivityIndicator size="large" color="#117a65" style={{ marginTop: 20 }} />
          ) : filteredData.length > 0 ? (
            filteredData.map((item) => {
              let statusColor = '#d4ac0d';
              let statusBg = '#fef9e7';
              let statusLabel = 'PENDING';

              if (item.status === 'setuju') {
                statusColor = '#117a65';
                statusBg = '#e8f3f1';
                statusLabel = 'PAID';
              } else if (item.status === 'tidak disetuju') {
                statusColor = '#e74c3c';
                statusBg = '#fceaea';
                statusLabel = 'REJECTED';
              }

              return (
                <TouchableOpacity 
                  key={item.id_kredit} 
                  activeOpacity={0.7}
                  onPress={() => {
                    if (item.status === 'tunggu') {
                      router.push({
                        pathname: '/owner/detail_kredit',
                        params: { 
                          id: item.id_kredit,
                          nama: item.nama,
                          tanggal: item.tanggal,
                          saldo: item.saldo_kredit,
                          keterangan: item.keterangan,
                          status: item.status,
                          jenis: item.jenis_pengeluaran
                        }
                      });
                    }
                  }}
                >
                  <View style={[styles.card, item.jenis_pengeluaran === 'tetap' && styles.cardBorderTetap, item.status !== 'tunggu' && { opacity: 0.9 }]}>
                    <View style={[styles.cardIconContainer, { backgroundColor: statusBg }]}>
                      <MaterialCommunityIcons 
                        name={item.jenis_pengeluaran === 'tetap' ? "file-check" : "cash-fast"} 
                        size={24} 
                        color={statusColor} 
                      />
                    </View>
                    
                    <View style={styles.cardInfo}>
                      <Text style={styles.cardTitle}>{item.nama}</Text>
                      <Text style={styles.cardSubtitle}>{item.tanggal} • {item.jenis_pengeluaran}</Text>
                    </View>

                    <View style={styles.cardAmountContainer}>
                      <Text style={[styles.currency, { color: statusColor }]}>Rp</Text>
                      <Text style={[styles.amount, { color: statusColor }]}>{formatRupiah(item.saldo_kredit)}</Text>
                      <View style={[styles.statusBadge, { backgroundColor: statusBg }]}>
                        <Text style={[styles.statusBadgeText, { color: statusColor }]}>{statusLabel}</Text>
                      </View>
                    </View>
                  </View>
                </TouchableOpacity>
              );
            })
          ) : (
            <Text style={styles.emptyText}>Tidak ada data ditemukan.</Text>
          )}
        </View>
      </ScrollView>
      <BottomNav activeScreen="Pengeluaran" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8faf9' },
  scrollContent: { paddingHorizontal: 20, paddingTop: 20, paddingBottom: 100 },
  header: { marginBottom: 25 },
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#117a65', marginBottom: 15 },
  subHeaderTitle: { fontSize: 22, fontWeight: '700', color: '#333' },
  headerDescription: { fontSize: 14, color: '#7f8c8d', marginTop: 5 },
  // Style Tambahan untuk Search
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    paddingHorizontal: 15,
    borderRadius: 12,
    marginBottom: 20,
    height: 50,
    elevation: 2,
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 5,
  },
  searchInput: {
    flex: 1,
    marginLeft: 10,
    fontSize: 14,
    color: '#333',
  },
  filterRow: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 25 },
  filterCard: { backgroundColor: '#fff', width: '48%', padding: 12, borderRadius: 12, elevation: 2, shadowColor: '#000', shadowOpacity: 0.05, shadowRadius: 5 },
  filterLabel: { fontSize: 10, fontWeight: 'bold', color: '#117a65', marginBottom: 5 },
  filterValueContainer: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  filterValue: { fontSize: 14, fontWeight: '600', color: '#333' },
  listContainer: { gap: 15 },
  card: { backgroundColor: '#fff', borderRadius: 15, padding: 15, flexDirection: 'row', alignItems: 'center', elevation: 2, shadowColor: '#000', shadowOpacity: 0.05, shadowRadius: 5 },
  cardBorderTetap: { borderTopWidth: 3, borderTopColor: '#fffd96' },
  cardIconContainer: { width: 45, height: 45, borderRadius: 22.5, justifyContent: 'center', alignItems: 'center', marginRight: 12 },
  cardInfo: { flex: 1 },
  cardTitle: { fontSize: 16, fontWeight: '700', color: '#333' },
  cardSubtitle: { fontSize: 11, color: '#7f8c8d' },
  cardAmountContainer: { alignItems: 'flex-end' },
  currency: { fontSize: 10, fontWeight: 'bold' },
  amount: { fontSize: 15, fontWeight: 'bold' },
  statusBadge: { paddingHorizontal: 8, paddingVertical: 2, borderRadius: 10, marginTop: 5 },
  statusBadgeText: { fontSize: 9, fontWeight: 'bold' },
  emptyText: { textAlign: 'center', color: '#7f8c8d', marginTop: 20 },
});

export default PengeluaranScreen;