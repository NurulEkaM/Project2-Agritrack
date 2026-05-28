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

const { width } = Dimensions.get('window');

export default function KaryawanDashboard() {
  const params = useLocalSearchParams();
  const router = useRouter();
  const [displayName, setDisplayName] = useState("Karyawan");

  useEffect(() => {
    const loadUserData = async () => {
      try {
        if (params.userName) {
          setDisplayName(Array.isArray(params.userName) ? params.userName[0] : params.userName);
        } else {
          const jsonValue = await AsyncStorage.getItem('user_session');
          if (jsonValue != null) {
            const responseData = JSON.parse(jsonValue);
            setDisplayName(responseData.user?.nama || "Karyawan");
          }
        }
      } catch (e) {
        setDisplayName("Karyawan");
      }
    };
    loadUserData();
  }, [params.userName]);

  const handleNavigation = (screenName: string) => {
    const routes: any = {
      'Absensi': '/karyawan/Absensi',
      'Home': '/karyawan',
      'Gaji': '/karyawan/gaji',
      'Profile': '/karyawan/profile',
      'Produk': '/karyawan/Produk'
    };
    if (routes[screenName]) router.push(routes[screenName]);
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      
      {/* 1. HEADER (Sesuai Gambar 1) */}
      <View style={styles.header}>
        <View style={styles.headerLeft}>
          <View style={styles.logoBox}>
            <Image 
              source={require('../../assets/images/Logo1.png')} 
              style={styles.logoImage}
              resizeMode="contain"
            />
          </View>
          <View>
            <Text style={styles.brandSubtitle}>AGRITRACK SYSTEM</Text>
            <Text style={styles.brandName}>KIWARI FARM</Text>
          </View>
        </View>
        <TouchableOpacity style={styles.ProfileButton} onPress={() => router.push('/karyawan/profile')}>
          <Ionicons name="person" size={22} color="#000000" />
          <View style={styles.ProfileNotifDot} />
        </TouchableOpacity>
      </View>

      <ScrollView 
        showsVerticalScrollIndicator={false} 
        contentContainerStyle={styles.scrollContent}
      >
        {/* 2. WELCOME CARD (Sesuai Gambar 1) */}
        <View style={styles.welcomeCard}>
          <View style={styles.welcomeTextContent}>
            <Text style={styles.greetingText}>Selamat Pagi, 👋</Text>
            <Text style={styles.profileName} numberOfLines={1}>{displayName}</Text>
            <View style={styles.activeBadge}>
              <Text style={styles.activeBadgeText}>Karyawan Aktif</Text>
            </View>
          </View>
          {/* Ikon Dekoratif Dashboard */}
          <MaterialCommunityIcons name="view-dashboard-outline" size={80} color="rgba(255,255,255,0.2)" style={styles.cardIllustration} />
        </View>

        {/* 3. MENU AKSES (Sesuai Gambar 2) */}
        <Text style={styles.sectionTitle}>MENU AKSES</Text>
        
        <TouchableOpacity style={styles.menuItem} onPress={() => router.push('/karyawan/Absensi')}>
          <View style={[styles.menuIconBg, { backgroundColor: '#E8F5E9' }]}>
            <MaterialCommunityIcons name="fingerprint" size={26} color="#117a65" />
          </View>
          <View style={styles.menuTextContent}>
            <Text style={styles.menuTitle}>Absensi</Text>
            <Text style={styles.menuSub}>Masuk & Pulang</Text>
          </View>
          <Ionicons name="chevron-forward-circle" size={20} color="#E0E0E0" />
        </TouchableOpacity>

        <TouchableOpacity style={styles.menuItem} onPress={() => router.push('/karyawan/gaji')}>
          <View style={[styles.menuIconBg, { backgroundColor: '#E0F2F1' }]}>
            <MaterialCommunityIcons name="bank-transfer" size={26} color="#00796B" />
          </View>
          <View style={styles.menuTextContent}>
            <Text style={styles.menuTitle}>Informasi Gaji</Text>
            <Text style={styles.menuSub}>Slip & Total Jam</Text>
          </View>
          <Ionicons name="chevron-forward-circle" size={20} color="#E0E0E0" />
        </TouchableOpacity>

        {/* 4. GPS ALERT (Sesuai Gambar 2) */}
        {/* <View style={styles.gpsAlert}>
          <Ionicons name="information-circle" size={18} color="#B8860B" />
          <Text style={styles.gpsText}>
            Pastikan mengaktifkan GPS sebelum melakukan Absensi kehadiran.
          </Text>
        </View> */}

        {/* 5. ELEMEN BARU: RINGKASAN KEHADIRAN (Visual Stat) */}
        <Text style={styles.sectionTitle}>RINGKASAN MINGGU INI</Text>
        <View style={styles.statsCard}>
          <View style={styles.statInfo}>
            <Text style={styles.statMainText}>85%</Text>
            <Text style={styles.statLabel}>Tingkat Kehadiran</Text>
          </View>
          <View style={styles.progressBarContainer}>
            <View style={styles.progressBarBg}>
              <View style={[styles.progressBarFill, { width: '85%' }]} />
            </View>
            <Text style={styles.progressDetail}>5 Hari Masuk / 6 Hari Kerja</Text>
          </View>
        </View>

        {/* 6. BUTUH BANTUAN (Sesuai Gambar 2) */}
        <Text style={styles.sectionTitle}>BUTUH BANTUAN?</Text>
        <TouchableOpacity 
          style={styles.supportCard}
          onPress={() => router.push('https://wa.me/628123456789')}
        >
          <View style={styles.supportIconBg}>
            <Ionicons name="headset" size={20} color="#FFF" />
          </View>
          <View style={styles.menuTextContent}>
            <Text style={styles.supportTitle}>Hubungi Layanan Admin</Text>
            <Text style={styles.supportSub}>kiwari@gmail.com (08.00 - 16.00)</Text>
          </View>
          <View style={styles.waBadge}>
            <Text style={styles.waBadgeText}>WhatsApp</Text>
          </View>
        </TouchableOpacity>

        <View style={{ height: 100 }} />
      </ScrollView>

      <BottomNav activeScreen="Home" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 15,
  },
  headerLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logoBox: {
    width: 40,
    height: 40,
    backgroundColor: '#F8F9FA',
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 10,
  },
  logoImage: {
    width: 30,
    height: 30,
  },
  brandSubtitle: {
    fontSize: 10,
    color: '#95A5A6',
    fontWeight: '600',
    letterSpacing: 1,
  },
  brandName: {
    fontSize: 16,
    fontWeight: '800',
    color: '#B8860B',
  },
  ProfileButton: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: '#F0F0F0',
    justifyContent: 'center',
    alignItems: 'center',
  },
  ProfileNotifDot: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: '#FF3B30',
    position: 'absolute',
    top: 4,
    right: 4,
  },
  scrollContent: {
    paddingHorizontal: 20,
  },
  welcomeCard: {
    backgroundColor: '#117a65',
    borderRadius: 25,
    padding: 22,
    flexDirection: 'row',
    alignItems: 'center',
    overflow: 'hidden',
    marginTop: 10,
    marginBottom: 25,
  },
  welcomeTextContent: {
    flex: 1,
    zIndex: 2,
  },
  greetingText: {
    color: '#A5D6A7',
    fontSize: 14,
    marginBottom: 5,
  },
  profileName: {
    color: '#FFFFFF',
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 12,
  },
  activeBadge: {
    backgroundColor: 'rgba(255,255,255,0.2)',
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: 15,
    alignSelf: 'flex-start',
  },
  activeBadgeText: {
    color: '#FFFFFF',
    fontSize: 11,
    fontWeight: '600',
  },
  cardIllustration: {
    position: 'absolute',
    right: -10,
    bottom: -10,
  },
  sectionTitle: {
    fontSize: 12,
    fontWeight: '800',
    color: '#7F8C8D',
    letterSpacing: 0.5,
    marginBottom: 12,
    marginTop: 5,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    padding: 15,
    borderRadius: 20,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 10,
    elevation: 2,
  },
  menuIconBg: {
    width: 50,
    height: 50,
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 15,
  },
  menuTextContent: {
    flex: 1,
  },
  menuTitle: {
    fontSize: 16,
    fontWeight: '700',
    color: '#2C3E50',
  },
  menuSub: {
    fontSize: 12,
    color: '#95A5A6',
    marginTop: 2,
  },
  gpsAlert: {
    flexDirection: 'row',
    backgroundColor: '#FFFBE6',
    padding: 15,
    borderRadius: 18,
    borderWidth: 1,
    borderColor: '#FFE58F',
    alignItems: 'center',
    marginBottom: 25,
  },
  gpsText: {
    flex: 1,
    fontSize: 12,
    color: '#B8860B',
    fontWeight: '600',
    marginLeft: 10,
    lineHeight: 18,
  },
  statsCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 20,
    padding: 18,
    marginBottom: 25,
  },
  statInfo: {
    flexDirection: 'row',
    alignItems: 'baseline',
    marginBottom: 10,
  },
  statMainText: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#117a65',
    marginRight: 8,
  },
  statLabel: {
    fontSize: 13,
    color: '#7F8C8D',
  },
  progressBarContainer: {
    width: '100%',
  },
  progressBarBg: {
    height: 8,
    backgroundColor: '#E0E0E0',
    borderRadius: 4,
    marginBottom: 8,
  },
  progressBarFill: {
    height: '100%',
    backgroundColor: '#117a65',
    borderRadius: 4,
  },
  progressDetail: {
    fontSize: 11,
    color: '#95A5A6',
    fontWeight: '500',
  },
  supportCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    padding: 15,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    elevation: 1,
  },
  supportIconBg: {
    width: 42,
    height: 42,
    borderRadius: 12,
    backgroundColor: '#2196F3',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 15,
  },
  supportTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: '#2C3E50',
  },
  supportSub: {
    fontSize: 11,
    color: '#95A5A6',
  },
  waBadge: {
    backgroundColor: '#E8F5E9',
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 8,
  },
  waBadgeText: {
    fontSize: 10,
    color: '#117a65',
    fontWeight: '800',
  },
});