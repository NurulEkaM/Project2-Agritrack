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
  container: { flex: 1, backgroundColor: '#f8faf9' },
  scrollContent: { paddingHorizontal: 20, paddingTop: 20, paddingBottom: 110 },
  header: { marginBottom: 20 },
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#117a65', marginBottom: 5 },
  subHeaderTitle: { fontSize: 22, fontWeight: '700', color: '#333' },
  searchContainer: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#f0f2f1', borderRadius: 12, paddingHorizontal: 15, height: 50, marginBottom: 25 },
  searchIcon: { marginRight: 10 },
  searchInput: { flex: 1, fontSize: 15, color: '#333' },
  listContainer: { gap: 15 },
  card: { backgroundColor: '#fff', borderRadius: 12, overflow: 'hidden', elevation: 4, shadowColor: '#000', shadowOpacity: 0.08, shadowRadius: 5 },
  cardHeaderAccent: { height: 4, backgroundColor: '#f9d976' },
  cardBody: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', padding: 20 },
  employeeName: { fontSize: 16, fontWeight: 'bold', color: '#333' },
  employeeRole: { fontSize: 12, color: '#95a5a6', marginTop: 3 },
  viewDetailsText: { fontSize: 12, fontWeight: 'bold', color: '#117a65' },
  fab: { position: 'absolute', right: 20, bottom: 90, backgroundColor: '#f9d976', width: 60, height: 60, borderRadius: 30, justifyContent: 'center', alignItems: 'center', elevation: 8 },
  emptyText: { textAlign: 'center', color: '#95a5a6', marginTop: 40 },
});

export default KaryawanScreen;