import React, { useState, useCallback } from 'react'; // Tambahkan useCallback
import {
  StyleSheet,
  Text,
  View,
  ScrollView,
  SafeAreaView,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { Ionicons, MaterialIcons } from '@expo/vector-icons';
import { useRouter, useFocusEffect } from 'expo-router'; // Tambahkan useFocusEffect
import BottomNav from './components/BottomNav';

interface Karyawan {
  id_user: number;
  nama: string;
  jabatan: string;
  role: string;
}

const KaryawanScreen = () => {
  const router = useRouter();
  const [dataKaryawan, setDataKaryawan] = useState<Karyawan[]>([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const API_URL = 'http://10.0.2.2:8000/api/users'; 

  const fetchDataKaryawan = async () => {
    try {
      const response = await fetch(API_URL);
      const json = await response.json();
      // Mengambil data dan memastikan urutan terbaru di atas jika API belum mengurutkan
      setDataKaryawan(json.results || []);
    } catch (error) {
      console.error('Error fetching users:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  // INI KUNCINYA: Memanggil API setiap kali layar ini tampil kembali
  useFocusEffect(
    useCallback(() => {
      fetchDataKaryawan();
    }, [])
  );

  const onRefresh = () => {
    setRefreshing(true);
    fetchDataKaryawan();
  };

  const filteredKaryawan = dataKaryawan.filter((item) => {
    const nama = item.nama ? item.nama.toLowerCase() : "";
    const jabatan = item.jabatan ? item.jabatan.toLowerCase() : "";
    const search = searchQuery.toLowerCase();
    return nama.includes(search) || jabatan.includes(search);
  });

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView
        contentContainerStyle={styles.scrollContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#117a65" />
        }
      >
        <View style={styles.header}>
          <Text style={styles.headerTitle}>PEKERJA</Text>
          <Text style={styles.subHeaderTitle}>Karyawan Kiwari Farm</Text>
        </View>

        <View style={styles.searchContainer}>
          <Ionicons name="search-outline" size={20} color="#95a5a6" style={styles.searchIcon} />
          <TextInput
            style={styles.searchInput}
            placeholder="Search by name or role..."
            placeholderTextColor="#bdc3c7"
            value={searchQuery}
            onChangeText={(text) => setSearchQuery(text)}
          />
        </View>

        <View style={styles.listContainer}>
          {loading ? (
            <ActivityIndicator size="large" color="#117a65" style={{ marginTop: 20 }} />
          ) : filteredKaryawan.length > 0 ? (
            filteredKaryawan.map((item) => (
              <View key={item.id_user.toString()} style={styles.card}>
                <View style={styles.cardHeaderAccent} />
                <View style={styles.cardBody}>
                  <View style={{ flex: 1 }}>
                    <Text style={styles.employeeName}>
                      {item.nama ? item.nama.toUpperCase() : 'TANPA NAMA'}
                    </Text>
                    <Text style={styles.employeeRole}>
                      {item.jabatan ? item.jabatan.toUpperCase() : 'STAFF'}
                    </Text>
                  </View>
                  <TouchableOpacity 
                    onPress={() => router.push({
                      pathname: "/owner/detail_karyawan/[id]",
                      params: { id: item.id_user }
                    })}
                  >
                    <Text style={styles.viewDetailsText}>VIEW DETAILS</Text>
                  </TouchableOpacity>
                </View>
              </View>
            ))
          ) : (
            <Text style={styles.emptyText}>Tidak ada karyawan ditemukan.</Text>
          )}
        </View>
      </ScrollView>

      <TouchableOpacity 
        style={styles.fab}
        onPress={() => router.push('/owner/tambah_karyawan')}
      >
        <MaterialIcons name="person-add-alt-1" size={26} color="#117a65" />
      </TouchableOpacity>
      
      <BottomNav
        activeScreen="Karyawan"
        onNavPress={(screen) => {
          if (screen === 'Pengeluaran') router.push('/owner/pengeluaran');
          else if (screen === 'Home') router.push('/owner');
          else if (screen === 'Laporan') router.push('/owner/laporan');
          else if (screen === 'Profile') router.push('/owner/profile');
        }}
      />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F4F7F6' }, // Sedikit lebih keabu-abuan agar putih card lebih muncul
  scrollContent: { paddingHorizontal: 20, paddingTop: 20, paddingBottom: 120 },
  header: { marginBottom: 25 },
  headerTitle: { fontSize: 14, fontWeight: '700', color: '#117a65', letterSpacing: 1, marginBottom: 5 },
  subHeaderTitle: { fontSize: 26, fontWeight: '800', color: '#2C3E50' },
  
  // Search bar yang lebih modern
  searchContainer: { 
    flexDirection: 'row', 
    alignItems: 'center', 
    backgroundColor: '#FFFFFF', 
    borderRadius: 15, 
    paddingHorizontal: 15, 
    height: 55, 
    marginBottom: 25,
    borderWidth: 1,
    borderColor: '#E1E8E8'
  },
  searchIcon: { marginRight: 10 },
  searchInput: { flex: 1, fontSize: 16, color: '#333' },
  
  // Card styling
  listContainer: { gap: 16 },
  card: { 
    backgroundColor: '#fff', 
    borderRadius: 16, 
    overflow: 'hidden', 
    // Shadow untuk iOS dan Android yang lebih elegan
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 3, 
  },
  cardHeaderAccent: { height: 6, backgroundColor: '#f9d976' }, // Sedikit lebih tebal aksennya
  cardBody: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', padding: 20 },
  employeeName: { fontSize: 17, fontWeight: '700', color: '#2C3E50', marginBottom: 4 },
  employeeRole: { fontSize: 13, fontWeight: '500', color: '#7F8C8D', backgroundColor: '#F0F3F3', alignSelf: 'flex-start', paddingHorizontal: 8, paddingVertical: 2, borderRadius: 6 },
  
  // Tombol detail
  viewDetailsText: { fontSize: 13, fontWeight: '700', color: '#117a65', letterSpacing: 0.5 },
  
  // FAB yang lebih menonjol
  fab: { 
    position: 'absolute', 
    right: 20, 
    bottom: 100, 
    backgroundColor: '#aeffef', // Diubah jadi hijau supaya lebih kontras (karena kuning kurang terlihat di beberapa layar)
    width: 60, 
    height: 60, 
    borderRadius: 30, 
    justifyContent: 'center', 
    alignItems: 'center', 
    shadowColor: '#117a65',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 6,
    elevation: 8 
  },
  emptyText: { textAlign: 'center', color: '#95a5a6', marginTop: 40, fontSize: 16 },
});

export default KaryawanScreen;