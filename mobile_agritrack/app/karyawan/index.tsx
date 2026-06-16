/* eslint-disable react/jsx-no-duplicate-props */
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
  StatusBar,
  ActivityIndicator
} from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { 
  Ionicons, 
  MaterialCommunityIcons 
} from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';

// IMPORT BOTTOM NAV LOKAL
import BottomNav from './components/BottomNav';

const { width } = Dimensions.get('window');

export default function KaryawanDashboard() {
  const params = useLocalSearchParams();
  const router = useRouter();
  const [displayName, setDisplayName] = useState("Karyawan");
  const [loading, setLoading] = useState(true);
  
  // State untuk Statistik Absensi
  const [absensiStats, setAbsensiStats] = useState({
    hadir: 0,
    total: 5, // Asumsi 5 hari kerja dalam seminggu
    percent: 0
  });

  useEffect(() => {
    loadInitialData();
  }, [params.userName]);

  const loadInitialData = async () => {
    try {
      setLoading(true);
      // 1. Ambil Session User
      const jsonValue = await AsyncStorage.getItem('user_session');
      if (jsonValue != null) {
        const responseData = JSON.parse(jsonValue);
        const user = responseData.user;
        
        setDisplayName(user?.nama || "Karyawan");
        
        // 2. Ambil Statistik Kehadiran dari API
        if (user?.id_user) {
          await fetchAbsensiStats(user.id_user);
        }
      }
    } catch (e) {
      console.error("Gagal memuat data:", e);
    } finally {
      setLoading(false);
    }
  };

  const fetchAbsensiStats = async (userId: number) => {
    try {
      const response = await axios.get(`http://10.0.2.2:8000/api/absensi/stats?id_user=${userId}`);
      if (response.data.success) {
        setAbsensiStats({
          hadir: response.data.hadir,
          total: response.data.total_hari,
          percent: response.data.persentase
        });
      }
    } catch (error) {
      console.log("Koneksi API Stats Gagal:", error);
    }
  };

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
      
      {/* 1. HEADER */}
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
        <TouchableOpacity style={styles.profileButton} onPress={() => router.push('/karyawan/profile')}>
          <Ionicons name="person" size={20} color="#117a65" />
          <View style={styles.profileNotifDot} />
        </TouchableOpacity>
      </View>

      <ScrollView 
        showsVerticalScrollIndicator={false} 
        contentContainerStyle={styles.scrollContent}
      >
        {/* 2. WELCOME CARD */}
        <View style={styles.welcomeCard}>
          <View style={styles.welcomeTextContent}>
            <Text style={styles.greetingText}>Selamat Datang, 👋</Text>
            <Text style={styles.profileName} numberOfLines={1}>{displayName}</Text>
            <View style={styles.activeBadge}>
              <View style={styles.badgeDot} />
              <Text style={styles.activeBadgeText}>Karyawan Aktif</Text>
            </View>
          </View>
          <MaterialCommunityIcons name="view-dashboard-outline" size={90} color="rgba(255,255,255,0.15)" style={styles.cardIllustration} />
        </View>

        {/* 3. RINGKASAN KEHADIRAN */}
        <Text style={styles.sectionTitle}>KEHADIRAN MINGGU INI</Text>
        <View style={styles.statsCard}>
          {loading ? (
            <ActivityIndicator color="#117a65" style={{ padding: 10 }} />
          ) : (
            <>
              <View style={styles.statsHeaderRow}>
                <View style={styles.statInfo}>
                  <Text style={styles.statMainText}>{absensiStats.percent}%</Text>
                  <Text style={styles.statLabel}>Persentase Hadir</Text>
                </View>
                <View style={styles.statBadgeCount}>
                  <Text style={styles.statBadgeText}>
                    {absensiStats.hadir}/{absensiStats.total} Hari
                  </Text>
                </View>
              </View>
              
              <View style={styles.progressBarContainer}>
                <View style={styles.progressBarBg}>
                  <View style={[styles.progressBarFill, { width: `${absensiStats.percent}%` }]} />
                </View>
                <Text style={styles.progressDetail}>
                  Progres kehadiran Anda di minggu kerja ini. Rekam absensi tepat waktu!
                </Text>
              </View>
            </>
          )}
        </View>

        {/* 4. MENU AKSES (GRID STYLE) */}
        <Text style={styles.sectionTitle}>MENU UTAMA</Text>
        <View style={styles.menuGrid}>
          
          <TouchableOpacity style={styles.gridItem} onPress={() => router.push('/karyawan/Absensi')}>
            <View style={[styles.menuIconBg, { backgroundColor: '#E8F5E9' }]}>
              <MaterialCommunityIcons name="fingerprint" size={28} color="#117a65" />
            </View>
            <Text style={styles.menuTitle}>Absensi</Text>
            <Text style={styles.menuSub}>Masuk & Pulang</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.gridItem} onPress={() => router.push('/karyawan/gaji')}>
            <View style={[styles.menuIconBg, { backgroundColor: '#E0F2F1' }]}>
              <MaterialCommunityIcons name="bank-transfer" size={28} color="#00796B" />
            </View>
            <Text style={styles.menuTitle}>Slip Gaji</Text>
            <Text style={styles.menuSub}>Rincian & Total Jam</Text>
          </TouchableOpacity>

        </View>

        {/* 5. BUTUH BANTUAN */}
        <Text style={styles.sectionTitle}>PUSAT BANTUAN</Text>
        <TouchableOpacity 
          style={styles.supportCard}
          onPress={() => router.push('https://wa.me/628123456789')}
        >
          <View style={styles.supportIconBg}>
            <Ionicons name="logo-whatsapp" size={22} color="#FFF" />
          </View>
          <View style={styles.menuTextContent}>
            <Text style={styles.supportTitle}>Hubungi Admin</Text>
            <Text style={styles.supportSub}>Kendala absensi & operasional Kiwari</Text>
          </View>
          <Ionicons name="chevron-forward" size={16} color="#95A5A6" />
        </TouchableOpacity>

        <View style={{ height: 120 }} />
      </ScrollView>

      <BottomNav activeScreen="Home" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    backgroundColor: '#FAFBFC' 
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 15,
    backgroundColor: '#FFFFFF',
    borderBottomWidth: 1,
    borderBottomColor: '#F0F0F0',
  },
  headerLeft: { 
    flexDirection: 'row', 
    alignItems: 'center' 
  },
  logoBox: {
    width: 42,
    height: 42,
    backgroundColor: '#F4F6F7',
    borderRadius: 10,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  logoImage: { 
    width: 28, 
    height: 28 
  },
  brandSubtitle: { 
    fontSize: 9, 
    color: '#95A5A6', 
    fontWeight: '700', 
    letterSpacing: 1.2 
  },
  brandName: { 
    fontSize: 15, 
    fontWeight: '800', 
    color: '#117a65' 
  },
  profileButton: {
    width: 38,
    height: 38,
    borderRadius: 12,
    backgroundColor: '#E8F5E9',
    justifyContent: 'center',
    alignItems: 'center',
  },
  profileNotifDot: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: '#E74C3C',
    position: 'absolute',
    top: -2,
    right: -2,
    borderWidth: 1.5,
    borderColor: '#FFFFFF'
  },
  scrollContent: { 
    paddingHorizontal: 20,
    paddingTop: 15
  },
  welcomeCard: {
    backgroundColor: '#117a65',
    borderRadius: 20,
    padding: 20,
    flexDirection: 'row',
    alignItems: 'center',
    overflow: 'hidden',
    marginBottom: 20,
    shadowColor: '#117a65',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 5,
    elevation: 3,
  },
  welcomeTextContent: { 
    flex: 1, 
    zIndex: 2 
  },
  greetingText: { 
    color: '#C8E6C9', 
    fontSize: 13, 
    fontWeight: '500',
    marginBottom: 4 
  },
  profileName: { 
    color: '#FFFFFF', 
    fontSize: 22, 
    fontWeight: 'bold', 
    marginBottom: 12 
  },
  activeBadge: {
    backgroundColor: 'rgba(255,255,255,0.18)',
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 20,
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
  },
  badgeDot: {
    width: 6,
    height: 6,
    borderRadius: 3,
    backgroundColor: '#2ECC71',
    marginRight: 6,
  },
  activeBadgeText: { 
    color: '#FFFFFF', 
    fontSize: 11, 
    fontWeight: '600' 
  },
  cardIllustration: { 
    position: 'absolute', 
    right: -10, 
    bottom: -10 
  },
  sectionTitle: {
    fontSize: 12,
    fontWeight: '800',
    color: '#95A5A6',
    letterSpacing: 0.8,
    marginBottom: 12,
    marginTop: 10,
  },
  statsCard: { 
    backgroundColor: '#FFFFFF', 
    borderRadius: 18, 
    padding: 16, 
    marginBottom: 20,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 3,
    elevation: 1,
  },
  statsHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  statInfo: { 
    flexDirection: 'row', 
    alignItems: 'baseline' 
  },
  statMainText: { 
    fontSize: 26, 
    fontWeight: 'bold', 
    color: '#117a65', 
    marginRight: 6 
  },
  statLabel: { 
    fontSize: 12, 
    color: '#7F8C8D',
    fontWeight: '500'
  },
  statBadgeCount: {
    backgroundColor: '#F4F6F7',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 8,
  },
  statBadgeText: {
    fontSize: 12,
    fontWeight: '700',
    color: '#2C3E50',
  },
  progressBarContainer: { 
    width: '100%' 
  },
  progressBarBg: { 
    height: 8, 
    backgroundColor: '#EAEDED', 
    borderRadius: 4, 
    marginBottom: 8 
  },
  progressBarFill: { 
    height: '100%', 
    backgroundColor: '#117a65', 
    borderRadius: 4 
  },
  progressDetail: { 
    fontSize: 11, 
    color: '#95A5A6', 
    lineHeight: 15
  },
  menuGrid: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  gridItem: {
    backgroundColor: '#FFFFFF',
    width: (width - 52) / 2, // Mengatur agar 2 kotak pas berjejer
    padding: 16,
    borderRadius: 18,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 3,
    elevation: 1,
  },
  menuIconBg: {
    width: 46,
    height: 46,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 12,
  },
  menuTitle: { 
    fontSize: 15, 
    fontWeight: '700', 
    color: '#2C3E50' 
  },
  menuSub: { 
    fontSize: 11, 
    color: '#95A5A6', 
    marginTop: 2 
  },
  supportCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    padding: 14,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 2,
    elevation: 1,
  },
  supportIconBg: {
    width: 40,
    height: 40,
    borderRadius: 12,
    backgroundColor: '#2ECC71', // Diubah ke hijau WhatsApp yang ramah
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  menuTextContent: {
    flex: 1,
    justifyContent: 'center'
  },
  supportTitle: { 
    fontSize: 14, 
    fontWeight: '700', 
    color: '#2C3E50' 
  },
  supportSub: { 
    fontSize: 11, 
    color: '#95A5A6',
    marginTop: 1
  },
});