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
  ImageBackground
} from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { Ionicons, MaterialCommunityIcons, FontAwesome5 } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import BottomNav from './components/BottomNav';

const { width } = Dimensions.get('window');

export default function OwnerDashboard() {
  const params = useLocalSearchParams();
  const router = useRouter();
  const [userName, setUserName] = useState("Owner");

  useEffect(() => {
    const loadUserData = async () => {
      try {
        const jsonValue = await AsyncStorage.getItem('user_session');
        if (jsonValue != null) {
          const responseData = JSON.parse(jsonValue);
          setUserName(responseData.user?.nama || "Owner");
        }
      } catch (e) {
        console.error("Gagal memuat session:", e);
      }
    };
    loadUserData();
  }, []);

  const handleNavigation = (screenName: string) => {
    const routes: any = {
      Home: '/owner',
      Pengeluaran: '/owner/pengeluaran',
      Karyawan: '/owner/karyawan',
      Laporan: '/owner/laporan',
      Profile: '/owner/profile'
    };
    if (routes[screenName]) router.push(routes[screenName]);
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="light-content" />
      
      <ScrollView contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>
        
        {/* Header Section with Profile Trip */}
        <View style={styles.topSection}>
          <View style={styles.headerRow}>
            <View>
              <Text style={styles.welcomeTitle}>Welcome Back,</Text>
              <Text style={styles.ownerName}>{userName} 👋</Text>
            </View>
            <TouchableOpacity 
              onPress={() => router.push('/owner/profile')}
              style={styles.profileBadge}
            >
              <Image 
                source={{ uri: 'https://ui-avatars.com/api/?name=' + userName + '&background=0D8ABC&color=fff' }} 
                style={styles.avatarImg} 
              />
            </TouchableOpacity>
          </View>
        </View>

        {/* Financial Overview Card */}
        <ImageBackground 
          // source={require('../../assets/images/card-bg.png')} // Opsional: gunakan pattern/gradient
          style={styles.mainBalanceCard}
          imageStyle={{ borderRadius: 24 }}
        >
          <View style={styles.cardOverlay}>
            <View style={styles.balanceHeader}>
              <Text style={styles.balanceLabel}>TOTAL PENDAPATAN</Text>
              <MaterialCommunityIcons name="integrated-circuit-chip" size={32} color="#f1c40f" />
            </View>
            <Text style={styles.balanceValue}>Rp 428.500.000</Text>
            <View style={styles.balanceFooter}>
              <View style={styles.trendBox}>
                <Ionicons name="trending-up" size={16} color="#2ecc71" />
                <Text style={styles.trendText}>+12.5% Month</Text>
              </View>
              <Text style={styles.cardSubText}>Kiwari Farm Digital ID</Text>
            </View>
          </View>
        </ImageBackground>

        {/* Quick Stats Grid */}
        <View style={styles.statsGrid}>
          <TouchableOpacity 
            onPress={() => router.push('/owner/karyawan')}
            style={styles.gridItem}
          >
            <View style={[styles.iconCircle, { backgroundColor: '#e8f6f3' }]}>
              <Ionicons name="people" size={22} color="#117a65" />
            </View>
            <Text style={styles.gridValue}>142</Text>
            <Text style={styles.gridLabel}>Pekerja Aktif</Text>
          </TouchableOpacity>

          <TouchableOpacity 
            onPress={() => router.push('/owner/pengeluaran')}
            style={styles.gridItem}
          >
            <View style={[styles.iconCircle, { backgroundColor: '#fef5e7' }]}>
              <MaterialCommunityIcons name="chart-bell-curve-cumulative" size={22} color="#f39c12" />
            </View>
            <Text style={styles.gridValue}>28</Text>
            <Text style={styles.gridLabel}>Laporan Baru</Text>
          </TouchableOpacity>

          <View style={styles.gridItem}>
            <View style={[styles.iconCircle, { backgroundColor: '#fdf2f2' }]}>
              <FontAwesome5 name="pills" size={20} color="#e74c3c" />
            </View>
            <Text style={styles.gridValue}>85%</Text>
            <Text style={styles.gridLabel}>Stok Pupuk</Text>
          </View>
        </View>

        {/* Analytics Section */}
        <View style={styles.sectionTitleRow}>
          <Text style={styles.sectionTitle}>Business Analytics</Text>
          <TouchableOpacity><Text style={styles.seeAll}>Full Report</Text></TouchableOpacity>
        </View>

        <View style={styles.analyticsCard}>
          <View style={styles.chartHeader}>
             <View>
                <Text style={styles.chartTitle}>Daily Sales Performance</Text>
                <Text style={styles.chartSub}>Real-time tracking per day</Text>
             </View>
             <Ionicons name="ellipsis-vertical" size={20} color="#ccc" />
          </View>
          
          <View style={styles.barChartContainer}>
            {[40, 70, 45, 90, 65, 80, 50].map((height, i) => (
              <View key={i} style={styles.barWrapper}>
                <View style={[styles.bar, { height: height }]} />
                <Text style={styles.barDay}>{['M','T','W','T','F','S','S'][i]}</Text>
              </View>
            ))}
          </View>
        </View>

        {/* Operational Monitoring */}
        <Text style={styles.sectionTitle}>Monitoring Operasional</Text>
        
        <View>
          <TouchableOpacity style={styles.monitorCard}>
            <View style={styles.monitorIconBox}>
              <FontAwesome5 name="seedling" size={18} color="#117a65" />
            </View>
            <View style={styles.monitorInfo}>
              <Text style={styles.monitorTitle}>Pembelian Bibit</Text>
              <Text style={styles.monitorStatus}>12 Menunggu Persetujuan</Text>
            </View>
            <View style={styles.actionBadge}><Text style={styles.badgeTxt}>Review</Text></View>
          </TouchableOpacity>

          <TouchableOpacity style={styles.monitorCard}>
            <View style={[styles.monitorIconBox, { backgroundColor: '#fef9e7' }]}>
              <FontAwesome5 name="dragon" size={18} color="#f1c40f" />
            </View>
            <View style={styles.monitorInfo}>
              <Text style={styles.monitorTitle}>Panen Buah Naga</Text>
              <Text style={styles.monitorStatus}>Estimasi: 450 Kg minggu ini</Text>
            </View>
            <Ionicons name="chevron-forward" size={18} color="#ccc" />
          </TouchableOpacity>
        </View>

        <View style={{ height: 100 }} />
      </ScrollView>

      <BottomNav activeScreen="Home" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F0F3F5',
  },
  scrollContent: {
    padding: 20,
  },
  topSection: {
    marginTop: Platform.OS === 'android' ? 10 : 0,
    marginBottom: 25,
  },
  headerRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  welcomeTitle: {
    fontSize: 14,
    color: '#7f8c8d',
    fontFamily: Platform.OS === 'ios' ? 'System' : 'sans-serif-medium',
  },
  ownerName: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  profileBadge: {
    borderWidth: 2,
    borderColor: '#fff',
    borderRadius: 15,
    elevation: 5,
  },
  avatarImg: {
    width: 45,
    height: 45,
    borderRadius: 12,
  },
  mainBalanceCard: {
    backgroundColor: '#0e6251',
    borderRadius: 24,
    padding: 24,
    marginBottom: 25,
    elevation: 8,
    overflow: 'hidden',
  },
  cardOverlay: {
    zIndex: 1,
  },
  balanceHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  balanceLabel: {
    color: '#d1f2eb',
    fontSize: 12,
    letterSpacing: 1.5,
    fontWeight: '600',
  },
  balanceValue: {
    color: '#fff',
    fontSize: 30,
    fontWeight: 'bold',
    marginBottom: 20,
  },
  balanceFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  trendBox: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(46, 204, 113, 0.2)',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 8,
  },
  trendText: {
    color: '#2ecc71',
    fontSize: 11,
    fontWeight: '600',
    marginLeft: 4,
  },
  cardSubText: {
    color: 'rgba(255,255,255,0.6)',
    fontSize: 10,
  },
  statsGrid: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 25,
  },
  gridItem: {
    width: (width - 60) / 3,
    backgroundColor: '#fff',
    borderRadius: 20,
    padding: 15,
    alignItems: 'center',
    elevation: 2,
  },
  iconCircle: {
    padding: 10,
    borderRadius: 14,
    marginBottom: 10,
  },
  gridValue: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  gridLabel: {
    fontSize: 10,
    color: '#95a5a6',
    marginTop: 2,
  },
  sectionTitleRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 15,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginBottom: 15,
  },
  seeAll: {
    fontSize: 12,
    color: '#117a65',
    fontWeight: 'bold',
  },
  analyticsCard: {
    backgroundColor: '#fff',
    borderRadius: 24,
    padding: 20,
    marginBottom: 25,
    elevation: 2,
  },
  chartHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  chartTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  chartSub: {
    fontSize: 11,
    color: '#bdc3c7',
  },
  barChartContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'flex-end',
    height: 100,
  },
  barWrapper: {
    alignItems: 'center',
  },
  bar: {
    width: 12,
    backgroundColor: '#117a65',
    borderRadius: 6,
  },
  barDay: {
    fontSize: 10,
    color: '#bdc3c7',
    marginTop: 8,
  },
  monitorCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 18,
    padding: 15,
    marginBottom: 12,
    elevation: 1,
  },
  monitorIconBox: {
    backgroundColor: '#e8f6f3',
    padding: 12,
    borderRadius: 14,
  },
  monitorInfo: {
    flex: 1,
    marginLeft: 15,
  },
  monitorTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  monitorStatus: {
    fontSize: 11,
    color: '#95a5a6',
    marginTop: 2,
  },
  actionBadge: {
    backgroundColor: '#117a65',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 10,
  },
  badgeTxt: {
    color: '#fff',
    fontSize: 10,
    fontWeight: 'bold',
  },
});