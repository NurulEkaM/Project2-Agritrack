import React, { useState } from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  TextInput, 
  TouchableOpacity, 
  Image, 
  Alert, 
  ActivityIndicator,
  Dimensions
} from 'react-native';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';

const { width } = Dimensions.get('window');

export default function LoginScreen() {
  const router = useRouter();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

const handleLogin = async () => {
    if (!username || !password) {
      Alert.alert('Eror', 'Username dan Password tidak boleh kosong!');
      return;
    }

    setLoading(true);

    try {
      const API_URL = 'http://10.0.2.2:8000/api/login'; 

      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ username, password }),
      });

      const data = await response.json();

      if (response.status === 200 && data.success) {
        Alert.alert('Sukses', data.message);
        
        // 2. SIMPAN RESPONSE LOGIN KE ASYNCSTORAGE (Ini kuncinya)
        // Data ini yang akan dibaca oleh halaman /karyawan saat refresh
        await AsyncStorage.setItem('user_session', JSON.stringify(data));

        const userRole = data.user.role.toLowerCase();
        const userName = data.user.nama; // Mengambil 'nama' dari response Laravel kamu
        const userId = data.user.id_user; // Mengambil 'id_user' dari response Laravel kamu
        const userGaji = data.user.gaji; // Mengambil 'gaji' dari response Laravel kamu
        const userJabatan = data.user.jabatan;
        const userAlamat = data.user.alamat;
        const userNoHp = data.user.no_hp;
        const userUsername = data.user.username;
        const userPassword = data.user.password;
        
        if (userRole === 'owner') {
          // 3. KIRIM JUGA SEBAGAI PARAMS (Supaya saat pertama masuk langsung instan muncul)
          router.replace({
            pathname: '/owner',
            params: { userName: userName, userId: userId, userGaji: userGaji, userJabatan: userJabatan, userAlamat: userAlamat, userNoHp: userNoHp, userUsername: userUsername, userPassword: userPassword }
          });
        } else if (userRole === 'karyawan') {
          // Kirim parameter nama ke dashboard karyawan
          router.replace({
            pathname: '/karyawan',
            params: { userName: userName, userId: userId, userGaji: userGaji, userJabatan: userJabatan, userAlamat: userAlamat, userNoHp: userNoHp, userUsername: userUsername, userPassword: userPassword }
          });
        } else {
          Alert.alert('Eror', 'Role pengguna tidak dikenali.');
        }
      } else {
        Alert.alert('Login Gagal', data.message || 'Kredensial salah');
      }
    } catch (error) {
      console.error(error);
      Alert.alert('Eror', 'Tidak dapat terhubung ke server backend.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      {/* Bagian Atas Hijau */}
      <View style={styles.topSection} />

      {/* Bagian Bawah Putih Melengkung */}
      <View style={styles.bottomSection}>
        
        {/* Kontainer Logo di Tengah Lengkungan */}
        <View style={styles.logoContainer}>
          <Image 
            source={require('../assets/images/Logo1.png')} // Sesuaikan path logo Anda
            style={styles.logo}
            resizeMode="contain"
          />
        </View>

        {/* Teks Judul */}
        <Text style={styles.title}>Please Login to{"\n"}Your Account!</Text>

        {/* Form Input Username */}
        <TextInput
          style={styles.input}
          placeholder="Username"
          placeholderTextColor="#b0bec5"
          value={username}
          onChangeText={setUsername}
          autoCapitalize="none"
        />

        {/* Form Input Password */}
        <TextInput
          style={styles.input}
          placeholder="Password"
          placeholderTextColor="#b0bec5"
          secureTextEntry
          value={password}
          onChangeText={setPassword}
          autoCapitalize="none"
        />

        {/* Tombol Login */}
        <TouchableOpacity 
          style={styles.button} 
          onPress={handleLogin}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="#000" />
          ) : (
            <Text style={styles.buttonText}>LOGIN</Text>
          )
        }
        </TouchableOpacity>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#43a047', // Warna hijau atas dasar
  },
  topSection: {
    flex: 0.2, // Mengatur tinggi area hijau di atas
    backgroundColor: '#43a047',
  },
  bottomSection: {
    flex: 0.8,
    backgroundColor: '#ffffff',
    borderTopLeftRadius: 50,
    borderTopRightRadius: 50,
    alignItems: 'center',
    paddingHorizontal: 30,
    paddingTop: 60, // Memberi ruang agar teks tidak tertabrak logo atas
  },
  logoContainer: {
    position: 'absolute',
    top: -50, // Menggeser logo ke atas agar memotong garis lengkung tepat di tengah
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: '#ffffff',
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 4, // Shadow untuk android
    shadowColor: '#000', // Shadow untuk iOS
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.15,
    shadowRadius: 5,
  },
  logo: {
    width: 70,
    height: 70,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#000000',
    textAlign: 'center',
    lineHeight: 32,
    marginBottom: 40,
    fontFamily: 'poppins', // Menyelaraskan dengan font global Anda
  },
  input: {
    width: width * 0.85,
    height: 55,
    borderWidth: 1,
    borderColor: '#e0e0e0',
    borderRadius: 25,
    paddingHorizontal: 25,
    fontSize: 16,
    color: '#333',
    marginBottom: 20,
    backgroundColor: '#fff',
  },
  button: {
    width: width * 0.75,
    height: 50,
    backgroundColor: '#fbc02d', // Warna kuning emas tombol sesuai gambar
    borderRadius: 25,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 15,
    elevation: 2,
  },
  buttonText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#000000',
    letterSpacing: 1,
  },
});