import React, { useState, useEffect } from 'react';
import {
  StyleSheet,
  Text,
  View,
  TouchableOpacity,
  SafeAreaView,
  StatusBar,
  ScrollView,
  TextInput,
  ActivityIndicator,
  Alert,
  Platform
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import BottomNav from './components/BottomNav'; // Menggunakan BottomNav milik Anda

interface UserData {
  id_user: number;
  nama: string;
  jabatan: string;
  alamat: string;
  no_hp: string;
  role: string;
  gaji: number;
  username: string;
}

export default function ProfileScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(true);
  const [userData, setUserData] = useState<UserData | null>(null);
  const [password, setPassword] = useState<string>('');
  const [secureText, setSecureText] = useState<boolean>(true);
  const [updating, setUpdating] = useState<boolean>(false);

  useEffect(() => {
    loadUserData();
  }, []);

  // 1. Ambil data user yang sedang login dari AsyncStorage
  const loadUserData = async () => {
  try {
    const jsonValue = await AsyncStorage.getItem('user_session');
    console.log("DATA SESI TERPANTAU:", jsonValue); // Cek ini di terminal Expo Anda!

    if (jsonValue != null) {
      const sessionData = JSON.parse(jsonValue);
      
      // Pengecekan 1: Jika struktur datanya adalah { success: true, user: {...} }
      if (sessionData.user) {
        setUserData(sessionData.user);
      } 
      // Pengecekan 2: Jika data user langsung disimpan tanpa dibungkus object 'user'
      else if (sessionData.id_user || sessionData.nama) {
        setUserData(sessionData);
      }
      // Pengecekan 3: Jika backend Anda membungkusnya dengan nama 'results' atau 'data'
      else if (sessionData.results) {
        setUserData(sessionData.results);
      }
    } else {
      Alert.alert("Sesi Habis", "Data sesi tidak ditemukan, silahkan login kembali.");
    }
  } catch (error) {
    console.error("Gagal memuat sesi user:", error);
  } finally {
    setLoading(false);
  }
};

  // 2. Fungsi untuk inisial nama di dalam avatar (Ahmad Subarkah -> AS)
  const getInitials = (name: string) => {
    if (!name) return '??';
    const parts = name.split(' ');
    if (parts.length > 1) {
      return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return parts[0].substring(0, 2).toUpperCase();
  };

  // 3. Fungsi format singkatan gaji (9626192 -> 9.6M atau format Rupiah juta)
  const formatGajiSingkat = (angka?: number) => {
    if (!angka) return 'Rp 0';
    const juta = angka / 1000000;
    return `Rp ${juta.toFixed(1)}M`;
  };

  // 4. Logika Update Password ke API Laravel
  const handleUpdatePassword = async () => {
    if (!password || password.trim().length < 6) {
      Alert.alert('Peringatan', 'Password baru minimal harus 6 karakter.');
      return;
    }

    setUpdating(true);
    try {
      // Menggunakan id_user milik user yang login
      const API_URL = `http://10.0.2.2:8000/api/usersupdate/${userData?.id_user}`;
      const response = await fetch(API_URL, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          password: password,
        }),
      });

      const result = await response.json();

      if (response.ok) {
        Alert.alert('Sukses', 'Password Anda berhasil diperbarui!');
        setPassword('');
      } else {
        Alert.alert('Gagal', result.message || 'Gagal memperbarui password.');
      }
    } catch (error) {
      console.error(error);
      Alert.alert('Error', 'Terjadi kesalahan jaringan.');
    } finally {
      setUpdating(false);
    }
  };

  // 5. Fungsi Keluar Sesi (Logout)
  const handleLogout = async () => {
    Alert.alert(
      'Keluar Sesi',
      'Apakah Anda yakin ingin keluar dari akun ini?',
      [
        { text: 'Batal', style: 'cancel' },
        {
          text: 'Keluar',
          style: 'destructive',
          onPress: async () => {
            await AsyncStorage.removeItem('user_session');
            router.replace('/login'); // Arahkan kembali ke file login Anda
          },
        },
      ]
    );
  };

  // Logika navigasi navbar bawah diselaraskan dengan file Anda sebelumnya
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
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#117a65" />
      
      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>
        
        {/* --- BANNER ATAS GREEN GRADIENT --- */}
        <View style={styles.headerBanner}>
          {/* Tombol Back di Pojok Kiri Atas */}
          <TouchableOpacity style={styles.backButtonTop} onPress={() => router.push('/karyawan')}>
            <Ionicons name="arrow-back" size={22} color="#ffffff" />
          </TouchableOpacity>

          {/* Badge Role */}
          <View style={styles.roleBadge}>
            <Ionicons name="time-outline" size={12} color="#b8860b" style={{ marginRight: 3 }} />
            <Text style={styles.roleText}>{userData?.role || 'Karyawan'}</Text>
          </View>

          {/* Nama & Jabatan */}
          <View style={styles.profileMetaRow}>
            <View style={styles.metaLeft}>
              <Text style={styles.userNameText}>{userData?.nama || 'Nama Pengguna'}</Text>
              <View style={styles.jobRow}>
                <Ionicons name="briefcase-outline" size={14} color="rgba(255, 255, 255, 0.8)" style={{ marginRight: 5 }} />
                <Text style={styles.jobText}>{userData?.role || 'Jabatan'}</Text>
              </View>
            </View>

            {/* Avatar Bulat dengan Inisial Huruf */}
            <View style={styles.avatarCircle}>
              <Text style={styles.avatarInitials}>
                {getInitials(userData?.nama || '??')}
              </Text>
            </View>
          </View>
        </View>

        {/* --- STATS BOX (GAJI POKOK & MASA KERJA) --- */}
        <View style={styles.statsCardContainer}>
          <View style={styles.statsBox}>
            <Text style={styles.statsLabel}>GAJI POKOK</Text>
            <Text style={styles.statsValueColor}>{formatGajiSingkat(userData?.gaji)}</Text>
          </View>
          <View style={styles.verticalDivider} />
          <View style={styles.statsBox}>
            <Text style={styles.statsLabel}>Jabatan</Text>
            <Text style={styles.statsValueDark}>{userData?.jabatan || '-'}</Text>
          </View>
        </View>

        {/* --- INFORMASI KONTAK SECTION --- */}
        <View style={styles.sectionHeaderRow}>
          <Ionicons name="card-outline" size={16} color="#7f8c8d" />
          <Text style={styles.sectionHeaderTitle}>Informasi Kontak</Text>
        </View>

        <View style={styles.infoWhiteBox}>
          {/* Domisili / Alamat */}
          <View style={styles.infoRowItem}>
            <View style={styles.iconCircleBadge}>
              <Ionicons name="location-outline" size={18} color="#117a65" />
            </View>
            <View style={styles.infoTextContainer}>
              <Text style={styles.infoItemLabel}>DOMISILI</Text>
              <Text style={styles.infoItemValue}>{userData?.alamat || '-'}</Text>
            </View>
          </View>

          <View style={styles.horizontalLine} />

          {/* Whatsapp / No HP */}
          <View style={styles.infoRowItem}>
            <View style={styles.iconCircleBadge}>
              <Ionicons name="call-outline" size={16} color="#117a65" />
            </View>
            <View style={styles.infoTextContainer}>
              <Text style={styles.infoItemLabel}>WHATSAPP</Text>
              <Text style={styles.infoItemValue}>{userData?.no_hp || '-'}</Text>
            </View>
            
          </View>
        </View>

        {/* --- KEAMANAN & AKSES SECTION --- */}
        <View style={styles.sectionHeaderRow}>
          <Ionicons name="shield-checkmark-outline" size={16} color="#7f8c8d" />
          <Text style={styles.sectionHeaderTitle}>Keamanan & Akses</Text>
        </View>

        <View style={styles.infoWhiteBox}>
          {/* Form Username (Disabled) */}
          <View style={styles.inputContainerBlock}>
            <Text style={styles.inputFieldLabel}>USERNAME</Text>
            <View style={[styles.inputWrapper, styles.disabledInput]}>
              <Ionicons name="at" size={18} color="#7f8c8d" style={{ marginRight: 10 }} />
              <TextInput
                style={styles.textInputStyle}
                value={userData?.username || ''}
                editable={false}
                // color="#7f8c8d"
              />
            </View>
          </View>

          {/* Form Password Baru */}
          <View style={styles.inputContainerBlock}>
            <Text style={styles.inputFieldLabel}>PASSWORD BARU</Text>
            <View style={styles.inputWrapper}>
              <Ionicons name="lock-closed-outline" size={18} color="#117a65" style={{ marginRight: 10 }} />
              <TextInput
                style={styles.textInputStyle}
                placeholder="Masukkan password baru"
                placeholderTextColor="#bdc3c7"
                value={password}
                onChangeText={setPassword}
                secureTextEntry={secureText}
              />
              <TouchableOpacity onPress={() => setSecureText(!secureText)}>
                <Ionicons name={secureText ? "eye-outline" : "eye-off-outline"} size={18} color="#7f8c8d" />
              </TouchableOpacity>
            </View>
          </View>

          {/* Tombol Simpan Perubahan Password */}
          <TouchableOpacity 
            style={styles.saveChangeButton} 
            onPress={handleUpdatePassword}
            disabled={updating}
          >
            {updating ? (
              <ActivityIndicator color="#ffffff" size="small" />
            ) : (
              <>
                <Ionicons name="save-outline" size={16} color="#ffffff" style={{ marginRight: 8 }} />
                <Text style={styles.saveButtonText}>Simpan Perubahan</Text>
              </>
            )}
          </TouchableOpacity>
        </View>

        {/* --- BUTTON KELUAR SESI --- */}
        <TouchableOpacity style={styles.logoutButtonContainer} onPress={handleLogout}>
          <Ionicons name="log-out-outline" size={18} color="#e74c3c" style={{ marginRight: 6 }} />
          <Text style={styles.logoutButtonText}>Keluar Sesi</Text>
        </TouchableOpacity>

      </ScrollView>

      {/* --- BOTTOM NAVBAR --- */}
      {/* Mengunci penanda aktif ke halaman 'Profile' */}
      <BottomNav activeScreen="Profile" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
  },
  scrollContent: {
    paddingBottom: 100,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
  },
  headerBanner: {
    backgroundColor: '#117a65',
    paddingHorizontal: 20,
    paddingTop: Platform.OS === 'ios' ? 15 : 25,
    paddingBottom: 45,
    borderBottomLeftRadius: 5,
    borderBottomRightRadius: 5,
    position: 'relative',
  },
  backButtonTop: {
    position: 'absolute',
    top: 20,
    left: 15,
    zIndex: 10,
    padding: 5,
  },
  roleBadge: {
    backgroundColor: '#fef9e7',
    alignSelf: 'flex-start',
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: 15,
    marginTop: 35,
    marginBottom: 15,
    borderWidth: 0.5,
    borderColor: '#f5b041',
  },
  roleText: {
    fontSize: 11,
    fontWeight: 'bold',
    color: '#b8860b',
    textTransform: 'capitalize',
  },
  profileMetaRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  metaLeft: {
    flex: 1,
    paddingRight: 10,
  },
  userNameText: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#ffffff',
  },
  jobRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 5,
  },
  jobText: {
    fontSize: 13,
    color: 'rgba(255, 255, 255, 0.8)',
    fontWeight: '500',
  },
  avatarCircle: {
    width: 64,
    height: 64,
    borderRadius: 32,
    backgroundColor: 'rgba(255, 255, 255, 0.25)',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1.5,
    borderColor: '#ffffff',
  },
  avatarInitials: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#ffffff',
    letterSpacing: 0.5,
  },
  statsCardContainer: {
    backgroundColor: '#ffffff',
    marginHorizontal: 20,
    borderRadius: 16,
    paddingVertical: 15,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-around',
    marginTop: -25,
    elevation: 4,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 5,
  },
  statsBox: {
    alignItems: 'center',
    flex: 1,
  },
  verticalDivider: {
    width: 1,
    height: 30,
    backgroundColor: '#eaeded',
  },
  statsLabel: {
    fontSize: 10,
    color: '#95a5a6',
    fontWeight: 'bold',
    letterSpacing: 0.5,
  },
  statsValueColor: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#27ae60',
    marginTop: 4,
  },
  statsValueDark: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginTop: 4,
  },
  sectionHeaderRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginHorizontal: 20,
    marginTop: 22,
    marginBottom: 10,
  },
  sectionHeaderTitle: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#7f8c8d',
    marginLeft: 6,
  },
  infoWhiteBox: {
    backgroundColor: '#ffffff',
    marginHorizontal: 20,
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: '#f1f2f6',
  },
  infoRowItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 6,
  },
  iconCircleBadge: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: '#e8f8f5',
    justifyContent: 'center',
    alignItems: 'center',
  },
  infoTextContainer: {
    marginLeft: 12,
    flex: 1,
  },
  infoItemLabel: {
    fontSize: 9,
    color: '#95a5a6',
    fontWeight: 'bold',
    letterSpacing: 0.3,
  },
  infoItemValue: {
    fontSize: 13,
    color: '#2c3e50',
    fontWeight: '500',
    marginTop: 2,
    lineHeight: 18,
  },
  actionIconRight: {
    padding: 5,
  },
  horizontalLine: {
    height: 1,
    backgroundColor: '#f8f9fa',
    marginVertical: 12,
  },
  inputContainerBlock: {
    marginBottom: 15,
  },
  inputFieldLabel: {
    fontSize: 11,
    fontWeight: 'bold',
    color: '#7f8c8d',
    marginBottom: 6,
    letterSpacing: 0.3,
  },
  inputWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    borderWidth: 1,
    borderColor: '#e5e7eb',
    borderRadius: 10,
    paddingHorizontal: 12,
    height: 44,
  },
  disabledInput: {
    backgroundColor: '#f1f2f6',
    borderColor: '#e2e8f0',
  },
  textInputStyle: {
    flex: 1,
    fontSize: 13,
    color: '#2c3e50',
    fontWeight: '500',
    padding: 0,
  },
  saveChangeButton: {
    backgroundColor: '#117a65',
    borderRadius: 12,
    height: 44,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 5,
    elevation: 2,
    shadowColor: '#117a65',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.15,
    shadowRadius: 3,
  },
  saveButtonText: {
    color: '#ffffff',
    fontSize: 13,
    fontWeight: 'bold',
  },
  logoutButtonContainer: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 25,
    paddingVertical: 10,
    alignSelf: 'center',
  },
  logoutButtonText: {
    color: '#e74c3c',
    fontSize: 13,
    fontWeight: 'bold',
  },
});