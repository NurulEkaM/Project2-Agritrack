import React, { useState, useEffect } from 'react';
import {
  StyleSheet,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  SafeAreaView,
  ActivityIndicator,
  ScrollView,
  Alert
} from 'react-native';
import { Ionicons, MaterialIcons } from '@expo/vector-icons';
import { useLocalSearchParams, useRouter } from 'expo-router';

const DetailUserScreen = () => {
  const { id } = useLocalSearchParams();
  const router = useRouter();
  
  const [loading, setLoading] = useState(true);
  const [updating, setUpdating] = useState(false);

  // State Form langsung terhubung ke input
  const [nama, setNama] = useState('');
  const [jabatan, setJabatan] = useState('');
  const [role, setRole] = useState('');
  const [gaji, setGaji] = useState('');
  const [noHp, setNoHp] = useState('');
  const [alamat, setAlamat] = useState('');

// 1. Ubah URL Dasar (di dalam fungsi DetailUserScreen)
const API_URL = `http://10.0.2.2:8000/api/users/${id}`;
const API_URL_PUT = `http://10.0.2.2:8000/api/usersupdate/${id}`; // Tambahkan ini
const API_URL_DELETE = `http://10.0.2.2:8000/api/usersdelete/${id}`; // Tambahkan ini

// Fungsi Hapus Karyawan
const handleDelete = async () => {
  // Tampilkan konfirmasi sebelum menghapus
  Alert.alert(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus karyawan ini?",
    [
      { text: "Batal", style: "cancel" },
      {
        text: "Hapus",
        style: "destructive",
        onPress: async () => {
          setLoading(true); // Tampilkan loading saat proses hapus
          try {
            const response = await fetch(API_URL_DELETE, {
              method: 'DELETE',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
              },
            });

            const json = await response.json();

            if (response.ok) {
              Alert.alert("Sukses", "Data karyawan berhasil dihapus");
              // Setelah berhasil, arahkan kembali ke halaman daftar karyawan
              router.replace('/owner/karyawan'); 
            } else {
              Alert.alert("Gagal", json.message || "Gagal menghapus data");
              setLoading(false);
            }
          } catch (error) {
            Alert.alert("Error", "Koneksi ke server gagal");
            setLoading(false);
          }
        }
      }
    ]
  );
};

// 2. Ubah fungsi handleUpdate
const handleUpdate = async () => {
  setUpdating(true);
  try {
    const response = await fetch(API_URL_PUT, { // Gunakan API_URL_PUT
      method: 'PUT',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        nama,
        jabatan,
        role,
        gaji: parseInt(gaji), 
        no_hp: noHp,
        alamat,
      }),
    });

    const json = await response.json();

    if (response.ok) {
      Alert.alert("Sukses", "Data berhasil diperbarui");
      fetchUserData(); 
    } else {
      Alert.alert("Gagal", json.message || "Terjadi kesalahan");
    }
  } catch (error) {
    // Jika masih muncul error <, kita tangkap di sini
    Alert.alert("Error", "Cek koneksi server atau URL API");
    console.log(error);
  } finally {
    setUpdating(false);
  }
};

  const fetchUserData = async () => {
    try {
      const response = await fetch(API_URL);
      const json = await response.json();
      
      if (json.user) {
        setNama(json.user.nama);
        setJabatan(json.user.jabatan);
        setRole(json.user.role);
        setGaji(json.user.gaji.toString());
        setNoHp(json.user.no_hp);
        setAlamat(json.user.alamat);
      }
    } catch (error) {
      Alert.alert("Error", "Gagal mengambil data");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUserData();
  }, [id]);

  const getInitials = (name: string) => {
    if (!name) return '??';
    return name.split(' ').map((n) => n[0]).join('').toUpperCase().substring(0, 2);
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#117a65" />
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.topNav}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backBtn}>
          <Ionicons name="arrow-back" size={24} color="#117a65" />
        </TouchableOpacity>
        <Text style={styles.topNavTitle}>EDIT PROFIL KARYAWAN</Text>
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContainer}>
        
        <View style={styles.avatarWrapper}>
          <View style={styles.avatarCircle}>
            <Text style={styles.avatarText}>{getInitials(nama)}</Text>
          </View>
          <Text style={styles.nameLabel}>{nama || 'NAMA TIDAK ADA'}</Text>
          <Text style={styles.idLabel}>ID KARYAWAN: #00{id}</Text>
        </View>

        <View style={styles.infoSection}>
          <View style={styles.sectionHeader}>
            <MaterialIcons name="assignment" size={22} color="#117a65" />
            <Text style={styles.sectionHeaderText}>Informasi Pekerjaan</Text>
          </View>

          <Text style={styles.inputLabel}>JABATAN</Text>
          <TextInput 
            style={styles.inputBox} 
            value={jabatan} 
            onChangeText={setJabatan}
            placeholder="Masukkan jabatan..."
          />

          <Text style={styles.inputLabel}>ROLE AKSES</Text>
          <TextInput 
            style={styles.inputBox} 
            value={role} 
            onChangeText={setRole}
            placeholder="owner/karyawan"
          />

          <Text style={styles.inputLabel}>GAJI BULANAN (ANGKA)</Text>
          <TextInput 
            style={styles.inputBox} 
            value={gaji} 
            onChangeText={setGaji}
            keyboardType="numeric"
            placeholder="0"
          />

          <View style={[styles.sectionHeader, { marginTop: 20 }]}>
            <MaterialIcons name="contact-phone" size={22} color="#117a65" />
            <Text style={styles.sectionHeaderText}>Informasi Kontak & Pribadi</Text>
          </View>

          <Text style={styles.inputLabel}>NAMA LENGKAP</Text>
          <TextInput 
            style={styles.inputBox} 
            value={nama} 
            onChangeText={setNama}
            placeholder="Masukkan nama..."
          />

          <Text style={styles.inputLabel}>NOMOR TELEPON / WA</Text>
          <TextInput 
            style={styles.inputBox} 
            value={noHp} 
            onChangeText={setNoHp}
            keyboardType="phone-pad"
            placeholder="08..."
          />

          <Text style={styles.inputLabel}>ALAMAT DOMISILI</Text>
          <TextInput 
            style={[styles.inputBox, styles.textArea]} 
            value={alamat} 
            multiline 
            onChangeText={setAlamat}
            placeholder="Masukkan alamat lengkap..."
          />
        </View>

        <TouchableOpacity 
          style={styles.btnSave} 
          onPress={handleUpdate}
          disabled={updating}
        >
          {updating ? (
            <ActivityIndicator color="white" />
          ) : (
            <Text style={styles.btnSaveText}>SIMPAN PERUBAHAN</Text>
          )}
        </TouchableOpacity>

        <TouchableOpacity 
          style={styles.btnDelete} 
          onPress={handleDelete} // Panggil fungsi handleDelete
          disabled={updating}
        >
          <Text style={styles.btnDeleteText}>HAPUS KARYAWAN</Text>
        </TouchableOpacity>

        <Text style={styles.warningText}>
          Anda dapat langsung mengubah teks pada kolom di atas dan menekan simpan.
        </Text>
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  loadingContainer: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  topNav: { flexDirection: 'row', alignItems: 'center', padding: 20 },
  backBtn: { marginRight: 15 },
  topNavTitle: { fontSize: 16, fontWeight: 'bold', color: '#117a65', letterSpacing: 0.5 },
  scrollContainer: { paddingHorizontal: 25, paddingBottom: 40 },
  avatarWrapper: { alignItems: 'center', marginVertical: 20 },
  avatarCircle: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: '#117a65',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 4,
    borderColor: '#f9d976',
  },
  avatarText: { color: 'white', fontSize: 35, fontWeight: 'bold' },
  nameLabel: { fontSize: 22, fontWeight: 'bold', color: '#333', marginTop: 15 },
  idLabel: { fontSize: 13, color: '#95a5a6', fontWeight: '500' },
  infoSection: { marginTop: 10 },
  sectionHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 15, borderBottomWidth: 1, borderBottomColor: '#f0f0f0', paddingBottom: 10 },
  sectionHeaderText: { marginLeft: 10, fontSize: 15, fontWeight: 'bold', color: '#117a65' },
  inputLabel: { fontSize: 10, fontWeight: 'bold', color: '#bdc3c7', marginBottom: 5, marginLeft: 5 },
  inputBox: {
    backgroundColor: '#f9f9f9',
    borderRadius: 12,
    paddingHorizontal: 15,
    height: 50,
    marginBottom: 15,
    color: '#2c3e50',
    fontSize: 14,
    fontWeight: '500',
    borderWidth: 1,
    borderColor: '#eee'
  },
  textArea: { height: 80, textAlignVertical: 'top', paddingTop: 15 },
  btnSave: {
    backgroundColor: '#117a65',
    height: 55,
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 30,
  },
  btnSaveText: { color: 'white', fontWeight: 'bold', fontSize: 14 },
  btnDelete: {
    backgroundColor: '#fff',
    height: 55,
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 12,
    borderWidth: 1,
    borderColor: '#e74c3c'
  },
  btnDeleteText: { color: '#e74c3c', fontWeight: 'bold', fontSize: 14 },
  warningText: {
    textAlign: 'center',
    color: '#bdc3c7',
    fontSize: 11,
    marginTop: 25,
    lineHeight: 16,
  },
});

export default DetailUserScreen;