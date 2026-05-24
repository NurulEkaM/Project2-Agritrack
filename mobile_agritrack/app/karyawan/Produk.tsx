import React, { useEffect, useState } from 'react';
import { 
  StyleSheet, 
  View, 
  Text, 
  TextInput, 
  FlatList, 
  TouchableOpacity, 
  SafeAreaView,
  StatusBar,
  ActivityIndicator
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import BottomNav from './components/BottomNav'; 
import { router } from 'expo-router';

interface Produk {
  id_produk: number;
  nama_produk: string;
  harga_satuan: string;
  stok: number;
  deskripsi: string;
}

const ProdukPage = () => {
  const [produks, setProduks] = useState<Produk[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState<string>(''); // State untuk search

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      const response = await fetch('http://10.0.2.2:8000/api/Produk');
      const data = await response.json();
      setProduks(data);
      setLoading(false);
    } catch (error) {
      console.error("Gagal mengambil data produk:", error);
      setLoading(false);
    }
  };

  // LOGIKA FILTERING (PENCARIAN)
  const filteredData = produks.filter(item => {
    const query = searchQuery.toLowerCase().trim();
    if (!query) return true;

    return (
      item.nama_produk.toLowerCase().includes(query) ||
      (item.deskripsi && item.deskripsi.toLowerCase().includes(query)) ||
      item.harga_satuan.toString().includes(query)
    );
  });

  const handleNavigation = (screenName: string) => {
    if (screenName === 'Home') {
      router.push('/karyawan');
    } else if (screenName === 'Absensi') {
      router.push('/karyawan/Absensi');
    } else if (screenName === 'Gaji') { 
      router.push('/karyawan/gaji');
    } else if (screenName === 'Profile') {
      router.push('/karyawan/profile');
    } else if (screenName === 'Produk') {
      router.push('/karyawan/Produk');
    }
  };

  

  const renderProductItem = ({ item }: { item: Produk }) => (
    <TouchableOpacity 
      style={styles.card} 
      onPress={() => router.push({ pathname: '/karyawan/edit_produk', params: { ...item } })}
    >
      <View style={styles.imagePlaceholder}>
        <Ionicons name="leaf-outline" size={50} color="#117a65" opacity={0.3} />
      </View>
      <Text style={styles.productName} numberOfLines={1}>{item.nama_produk}</Text>
      <Text style={styles.productPrice}>Rp {Number(item.harga_satuan).toLocaleString('id-ID')}</Text>
      <View style={styles.stokBadge}>
        <Text style={styles.stokText}>Stok: {item.stok}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.menuTitle}>Menu</Text>
        <TouchableOpacity style={styles.bellIcon}>
          <Ionicons name="notifications-outline" size={24} color="black" />
        </TouchableOpacity>
      </View>

      <View style={styles.content}>
        <Text style={styles.subTitle}>Our Products</Text>
        <Text style={styles.mainTitle}>Special in Kiwari Farm</Text>

        {/* SEARCH BAR (LOGIKA SEPERTI ABSENSI) */}
        <View style={styles.searchContainer}>
          <Ionicons name="search-outline" size={20} color="#bdc3c7" style={{marginRight: 10}} />
          <TextInput 
            placeholder="Cari nama produk..." 
            style={styles.searchInput}
            placeholderTextColor="#bdc3c7"
            value={searchQuery}
            onChangeText={setSearchQuery} // Update state saat mengetik
            autoCapitalize="none"
          />
          {searchQuery.length > 0 && (
            <TouchableOpacity onPress={() => setSearchQuery('')}>
              <Ionicons name="close-circle" size={18} color="#bdc3c7" />
            </TouchableOpacity>
          )}
        </View>

        {/* Bagian Tombol Tambah di Sebelah Kanan */}
        <View style={styles.actionSection}>
          <TouchableOpacity 
            style={styles.addButton} 
            onPress={() => router.push('/karyawan/input_produk')} // Navigasi ke halaman input produk
          >
            <Ionicons name="add" size={30} color="black" />
          </TouchableOpacity>
        </View>

        {/* List Produk Menggunakan filteredData */}
        {loading ? (
          <View style={styles.loader}>
            <ActivityIndicator size="large" color="#117a65" />
            <Text style={{ marginTop: 10, color: '#bdc3c7' }}>Mengambil Data...</Text>
          </View>
        ) : (
          <FlatList
            data={filteredData} // Menggunakan data hasil filter
            renderItem={renderProductItem}
            keyExtractor={(item) => item.id_produk.toString()}
            numColumns={2}
            columnWrapperStyle={styles.row}
            showsVerticalScrollIndicator={false}
            contentContainerStyle={{ paddingBottom: 100 }}
            ListEmptyComponent={
                <Text style={styles.emptyText}>{`Produk "${searchQuery}" tidak ditemukan.`}</Text>
            }
          />
        )}
      </View>

      {/* Navbar Bawah */}
      <BottomNav activeScreen="Produk" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 10,
  },
  menuTitle: {
    fontSize: 18,
    fontWeight: 'bold',
  },
  bellIcon: {
    position: 'absolute',
    right: 20,
  },
  content: {
    flex: 1,
    paddingHorizontal: 20,
  },
  subTitle: {
    fontSize: 14,
    color: '#bdc3c7',
    marginTop: 10,
  },
  mainTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#117a65',
    marginBottom: 15,
  },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    borderRadius: 15,
    paddingHorizontal: 15,
    height: 50,
    borderWidth: 1,
    borderColor: '#eee',
  },
  searchInput: {
    flex: 1,
    fontSize: 16,
    color: '#2c3e50',
  },
  actionSection: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    alignItems: 'center',
    marginVertical: 15,
  },
  addButton: {
    backgroundColor: '#dcfce7',
    width: 50,
    height: 50,
    borderRadius: 25,
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 2,
  },
  row: {
    justifyContent: 'space-between',
  },
  card: {
    backgroundColor: '#fff',
    width: '47%',
    marginBottom: 20,
  },
  imagePlaceholder: {
    width: '100%',
    height: 150,
    backgroundColor: '#f8f9fa',
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10,
  },
  productName: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  productPrice: {
    fontSize: 14,
    color: '#117a65',
    fontWeight: 'bold',
    marginTop: 4,
  },
  stokBadge: {
    marginTop: 5,
    backgroundColor: '#f1f1f1',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 5,
    alignSelf: 'flex-start',
  },
  stokText: {
    fontSize: 10,
    color: '#7f8c8d',
  },
  loader: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyText: {
    textAlign: 'center',
    marginTop: 50,
    color: '#bdc3c7',
  },
});

export default ProdukPage;