import React, { useState } from 'react';
import {
  StyleSheet,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  SafeAreaView,
  ScrollView,
  Alert,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { Ionicons, MaterialIcons } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import { Picker } from '@react-native-picker/picker'; // Pastikan install: npx expo install @react-native-picker/picker

const TambahKaryawan = () => {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  // State sesuai kolom tabel users
  const [nama, setNama] = useState('');
  const [role, setRole] = useState('karyawan');
  const [jabatan, setJabatan] = useState('');
  const [noHp, setNoHp] = useState('');
  const [alamat, setAlamat] = useState('');
  const [gaji, setGaji] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const API_URL = 'http://10.0.2.2:8000/api/addnew';

  const handleTambah = async () => {
    // Validasi Sederhana
    if (!nama || !username || !password || !noHp) {
      Alert.alert("Error", "Mohon lengkapi field utama (Nama, No HP, Username, Password)");
      return;
    }

    setLoading(true);
    try {
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nama: nama,
          jabatan: jabatan,
          alamat: alamat,
          no_hp: noHp,
          role: role,
          gaji: parseInt(gaji) || 0,
          username: username,
          password: password,
        }),
      });

      const json = await response.json();

    if (response.ok) {
    Alert.alert("Sukses", "Pekerja baru berhasil didaftarkan");
    // Menggunakan replace memastikan halaman list merender ulang dengan useFocusEffect tadi
    router.replace('/owner/karyawan'); 
    }
    } catch (error) {
      Alert.alert("Error", "Gagal terhubung ke server");
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backBtn}>
          <Ionicons name="arrow-back" size={24} color="#117a65" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Pekerja Baru</Text>
      </View>

      <KeyboardAvoidingView 
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={{ flex: 1 }}
      >
        <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>
          
          <View style={styles.formCard}>
            <Text style={styles.sectionTitle}>Identitas Pekerja</Text>
            
            {/* Nama */}
            <Text style={styles.label}>NAMA LENGKAP</Text>
            <TextInput 
              style={styles.input} 
              placeholder="Enter full name" 
              value={nama}
              onChangeText={setNama}
            />

            {/* Role Picker */}
            <Text style={styles.label}>ROLE (AKSES)</Text>
            <View style={styles.pickerContainer}>
              <Picker
                selectedValue={role}
                onValueChange={(itemValue) => setRole(itemValue)}
                style={styles.picker}
              >
                <Picker.Item label="Karyawan" value="karyawan" />
                <Picker.Item label="Admin" value="admin" />
                <Picker.Item label="Owner" value="owner" />
              </Picker>
            </View>

            {/* Jabatan */}
            <Text style={styles.label}>JABATAN</Text>
            <TextInput 
              style={styles.input} 
              placeholder="Contoh: Ketua Kebun / Admin" 
              value={jabatan}
              onChangeText={setJabatan}
            />

            {/* Phone Number */}
            <Text style={styles.label}>PHONE NUMBER</Text>
            <TextInput 
              style={styles.input} 
              placeholder="+62..." 
              keyboardType="phone-pad"
              value={noHp}
              onChangeText={setNoHp}
            />

            {/* Alamat */}
            <Text style={styles.label}>ALAMAT</Text>
            <TextInput 
              style={[styles.input, styles.textArea]} 
              placeholder="Subang, Jawa Barat..." 
              multiline 
              value={alamat}
              onChangeText={setAlamat}
            />

            {/* Gaji */}
            <Text style={styles.label}>GAJI PER HARI / BULAN</Text>
            <TextInput 
              style={styles.input} 
              placeholder="100000" 
              keyboardType="numeric"
              value={gaji}
              onChangeText={setGaji}
            />

            <View style={styles.divider} />
            <Text style={styles.sectionTitle}>Kredensial Akun</Text>

            {/* Username */}
            <Text style={styles.label}>USERNAME</Text>
            <TextInput 
              style={styles.input} 
              placeholder="example123" 
              autoCapitalize="none"
              value={username}
              onChangeText={setUsername}
            />

            {/* Password */}
            <Text style={styles.label}>PASSWORD</Text>
            <TextInput 
              style={styles.input} 
              placeholder="******" 
              secureTextEntry
              value={password}
              onChangeText={setPassword}
            />

            {/* Submit Button */}
            <TouchableOpacity 
              style={styles.submitBtn} 
              onPress={handleTambah}
              disabled={loading}
            >
              {loading ? (
                <ActivityIndicator color="white" />
              ) : (
                <Text style={styles.submitText}>SUBMIT</Text>
              )}
            </TouchableOpacity>
          </View>

        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  header: { 
    flexDirection: 'row', 
    alignItems: 'center', 
    padding: 20, 
    backgroundColor: 'white',
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  backBtn: { marginRight: 15 },
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#333' },
  scrollContent: { padding: 20 },
  formCard: { 
    backgroundColor: 'white', 
    borderRadius: 20, 
    padding: 20,
    elevation: 3,
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 10,
  },
  sectionTitle: { fontSize: 16, fontWeight: 'bold', color: '#117a65', marginBottom: 20 },
  label: { fontSize: 11, fontWeight: 'bold', color: '#95a5a6', marginBottom: 8, marginTop: 10 },
  input: {
    backgroundColor: '#f8faf9',
    borderWidth: 1,
    borderColor: '#e0e0e0',
    borderRadius: 12,
    paddingHorizontal: 15,
    height: 50,
    fontSize: 14,
    color: '#333',
    marginBottom: 10
  },
  textArea: { height: 80, textAlignVertical: 'top', paddingTop: 15 },
  pickerContainer: {
    backgroundColor: '#f8faf9',
    borderWidth: 1,
    borderColor: '#e0e0e0',
    borderRadius: 12,
    marginBottom: 10,
    overflow: 'hidden'
  },
  picker: { height: 50, width: '100%' },
  divider: { height: 1, backgroundColor: '#f0f0f0', marginVertical: 25 },
  submitBtn: {
    backgroundColor: '#2ecc71',
    height: 55,
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 30,
    elevation: 5,
    shadowColor: '#2ecc71',
    shadowOpacity: 0.3,
    shadowOffset: { width: 0, height: 4 }
  },
  submitText: { color: 'white', fontWeight: 'bold', fontSize: 16, letterSpacing: 1 },
});

export default TambahKaryawan;