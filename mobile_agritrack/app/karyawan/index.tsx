import React, { useState, useEffect } from 'react';
import {
  StyleSheet,
  Text,
  View,
  ScrollView,
  Image,
  TouchableOpacity,
  SafeAreaView,
  Dimensions,
  Platform,
  StatusBar
} from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { 
  Ionicons, 
  MaterialCommunityIcons 
} from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

// IMPORT BOTTOM NAV LOKAL
import BottomNav from './components/BottomNav';
import { Colors } from '@/constants/theme';

const { width } = Dimensions.get('window');

export default function KaryawanDashboard() {
  const params = useLocalSearchParams();
  const router = useRouter();
  
  // State untuk menyimpan nama agar tidak hilang saat refresh
  const [displayName, setDisplayName] = useState(".");

  // Efek untuk memuat data pengguna
  useEffect(() => {
    const loadUserData = async () => {
      try {
        // 1. Coba ambil dari parameter navigasi dahulu (untuk respon cepat)
        if (params.userName) {
          const userName = Array.isArray(params.userName) ? params.userName[0] : params.userName;
          setDisplayName(userName);
        } else {
          // 2. Jika params kosong (akibat refresh), ambil dari AsyncStorage
          const jsonValue = await AsyncStorage.getItem('user_session');
          if (jsonValue != null) {
            const responseData = JSON.parse(jsonValue);
            // Mengambil field 'nama' sesuai response dari Laravel backend Anda
            setDisplayName(responseData.user?.nama || "Karyawan");
          } else {
            setDisplayName("Guest");
          }
        }
      } catch (e) {
        console.error("Gagal memuat session:", e);
        setDisplayName("Guest");
      }
    };

    loadUserData();
  }, [params.userName]);

  // Fungsi navigasi bottom bar
  // Ubah fungsi handleNavigation menjadi seperti ini:
  const handleNavigation = (screenName: string) => {
    if (screenName === 'Absensi') {
      router.push('/karyawan/Absensi'); // diarahkan ke file Absensi.tsx kamu
    } else if (screenName === 'Home') {
      router.push('/karyawan'); // tetap di index.tsx
    } else if (screenName === 'Gaji') {
      router.push('/karyawan/gaji'); // diarahkan ke file Calendar.tsx (jika ada)
    } else if (screenName === 'Profile') {
      router.push('/karyawan/profile'); // diarahkan ke file Profile.tsx (jika ada)
    } else if (screenName === 'Produk') {
      router.push('/karyawan/Produk'); // diarahkan ke file Produk.tsx (jika ada)
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#f8f9fa" />
      
      {/* 1. HEADER UTAMA */}
      <View style={styles.headerContainer}>
        <View style={styles.headerLeft}>
            <Image 
              source={require('../../assets/images/Logo1.png')} 
              style={styles.logoImage}
              resizeMode="contain"
            />
          <Text style={styles.brandName}>KIWARI FRAM</Text>
        </View>
        <TouchableOpacity style={styles.profileButton}
         onPress={() => router.push('/karyawan/profile')}
        >
          <Ionicons name="person-outline" size={22} color="#333" />
        </TouchableOpacity>
      </View>

      <ScrollView 
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* 2. WELCOME / PROFILE CARD */}
        <TouchableOpacity style={styles.welcomeCard}
          onPress={() => router.push('/karyawan/profile')}
        >
          <View style={styles.welcomeTextContainer}>
            <Text style={styles.greetingText}>Good Morning,</Text>
            <Text style={styles.profileName}>{displayName}</Text>
            <Text >Selamat datang di aplikasi Agritrack</Text>
          </View>
          <View style={styles.cardCircleDecorative} />
        </TouchableOpacity>

        {/* 3. METRICS STATS SECTION */}
        <View style={styles.statsRow}>
          <TouchableOpacity style={styles.statBox} onPress={() => router.push('/karyawan/Absensi')}>
            <View style={styles.statHeaderRow}>
              <View style={[styles.iconWrapper, { backgroundColor: '#fdf6e2' }]}>
                <Ionicons name="time-outline" size={18} color="#b8860b" />
              </View>
            </View>
            <Text style={styles.statLabel}>Buay Absen Baru?</Text>
            <Text style={styles.statValue}>
              Absensi
            </Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.statBox} onPress={() => router.push('/karyawan/gaji')}>
            <View style={styles.statHeaderRow}>
              <View style={[styles.iconWrapper, { backgroundColor: '#e8f8f5' }]}>
                <Ionicons name="trending-up-outline" size={18} color="#117a65" />
              </View>
              {/* <View style={styles.ringChart} /> */}
            </View>
            <Text style={styles.statLabel}>Informasi Gaji</Text>
            <Text style={styles.statValue}>Gajian!!</Text>
          </TouchableOpacity>
        </View>

        {/* 4. TODAY'S TASKS TITLE */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>Need Help?</Text>
          {/* <TouchableOpacity style={styles.viewScheduleBtn}>
            <Text style={styles.viewScheduleText}>View Schedule</Text>
            <Ionicons name="arrow-forward-outline" size={14} color="#117a65" />
          </TouchableOpacity> */}
        </View>

        {/* 5. LIST DAFTAR TUGAS */}
        <TouchableOpacity style={[styles.taskCard, { borderLeftColor: '#10e633' }]} onPress={() => router.push('https://wa.me/628123456789?text=Halo%20Admin%20KIWARI%20FRAM%2C%20saya%20ingin%20bertanya%20tentang%20absensi.')}>
          <View style={[styles.taskIconCircle, { backgroundColor: '#fdf2f2' }]}>
            <MaterialCommunityIcons name="email-outline" size={24} color="#009407" />
          </View>
          <View style={styles.taskDetails}>
            <Text style={styles.taskTitle}>kiwar@gmail.com</Text>
            <View style={styles.taskMetaRow}>
              <Ionicons name="time-outline" size={12} color="#7f8c8d" style={{ marginRight: 3 }} />
              <Text style={styles.taskMetaText}>buka 08.00 - 16.00</Text>
            </View>
          </View>
          <View style={[styles.tagBadge, { backgroundColor: '#fdf2f2' }]}>
            <Text style={[styles.tagText, { color: '#c0392b' }]}>URGENT</Text>
          </View>
          <Ionicons name="chevron-forward" size={16} color="#bdc3c7" />
        </TouchableOpacity>
      </ScrollView>

      {/* 6. BOTTOM NAVIGATION COMPONENT */}
      <BottomNav activeScreen="Home" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
    paddingTop: Platform.OS === 'android' ? StatusBar.currentHeight : 0,
  },
  headerContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 12,
    backgroundColor: '#f8f9fa',
  },
  headerLeft: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },

  logoImage: {
    width: 65,
    height: 65,

  },
  brandName: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#c08607',
  },
  profileButton: {
    width: 36,
    height: 36,
    borderRadius: 18,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#eaeaea',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 10,
  },
  welcomeCard: {
    width: '100%',
    backgroundColor: '#ffffff',
    borderRadius: 20,
    padding: 20,
    marginBottom: 20,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
  },
  welcomeTextContainer: {
    zIndex: 2,
  },
  greetingText: {
    fontSize: 13,
    color: '#27ae60',
    fontWeight: '600',
    marginBottom: 4,
  },
  profileName: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginBottom: 8,
  },
  cardCircleDecorative: {
    position: 'absolute',
    top: -40,
    right: -30,
    width: 130,
    height: 130,
    borderRadius: 65,
    backgroundColor: '#f4fbf7',
    zIndex: 1,
  },
  statsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 25,
  },
  statBox: {
    width: (width - 55) / 2,
    backgroundColor: '#ffffff',
    borderRadius: 18,
    padding: 16,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
  },
  statHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 15,
  },
  iconWrapper: {
    width: 34,
    height: 34,
    borderRadius: 10,
    justifyContent: 'center',
    alignItems: 'center',
  },
  miniChart: {
    flexDirection: 'row',
    alignItems: 'flex-end',
    width: 35,
    height: 22,
    justifyContent: 'space-between',
  },
  chartBar: {
    width: 5,
    borderRadius: 2,
  },
  ringChart: {
    width: 24,
    height: 24,
    borderRadius: 12,
    borderWidth: 3,
    borderColor: '#117a65',
    backgroundColor: 'transparent',
  },
  statLabel: {
    fontSize: 12,
    color: '#7f8c8d',
    marginBottom: 4,
  },
  statValue: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  statSubValue: {
    fontSize: 12,
    color: '#95a5a6',
    fontWeight: 'normal',
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 15,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1a252f',
  },
  viewScheduleBtn: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  viewScheduleText: {
    fontSize: 12,
    color: '#117a65',
    fontWeight: '600',
    marginRight: 4,
  },
  taskCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 14,
    padding: 14,
    marginBottom: 12,
    borderLeftWidth: 4,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.04,
    shadowRadius: 6,
  },
  taskIconCircle: {
    width: 44,
    height: 44,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  taskDetails: {
    flex: 1,
  },
  taskTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginBottom: 4,
  },
  taskMetaRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  taskMetaText: {
    fontSize: 11,
    color: '#7f8c8d',
  },
  tagBadge: {
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
    marginRight: 8,
  },
  tagText: {
    fontSize: 9,
    fontWeight: 'bold',
  },
});