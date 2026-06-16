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
  ImageBackground,
  ActivityIndicator
} from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons, MaterialCommunityIcons, FontAwesome5 } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { LineChart } from "react-native-chart-kit";
import BottomNav from './components/BottomNav';

const { width } = Dimensions.get('window');

export default function OwnerDashboard() {
  const router = useRouter();
  const [userName, setUserName] = useState("Owner");
  const [loading, setLoading] = useState(true);
  const [stats, setStats] = useState<any>({
    total_pendapatan: 0,
    pekerja_aktif: 0,
    laporan_baru: 0,
    chart_data: []
  });

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      setLoading(true);
      const session = await AsyncStorage.getItem('user_session');
      if (session) {
        const responseData = JSON.parse(session);
        setUserName(responseData.user?.nama || "Owner");
      }

      const response = await fetch('http://10.0.2.2:8000/api/owner/dashboard-stats');
      const json = await response.json();
      setStats(json);
    } catch (e) {
      console.error("Gagal memuat data dashboard:", e);
    } finally {
      setLoading(false);
    }
  };

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

  const formatRupiah = (value: number) => {
    if (value === null || value === undefined) return '0';
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <ScrollView contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>
        
        {/* Header Section */}
        <View style={styles.topSection}>
          <View style={styles.headerRow}>
            <View>
              <Text style={styles.welcomeTitle}>Welcome Back,</Text>
              <Text style={styles.ownerName}>{userName} 👋</Text>
            </View>
            <TouchableOpacity onPress={() => router.push('/owner/profile')} style={styles.profileBadge}>
              <Image 
                source={{ uri: `https://ui-avatars.com/api/?name=${userName}&background=0D8ABC&color=fff` }} 
                style={styles.avatarImg} 
              />
            </TouchableOpacity>
          </View>
        </View>

        {/* Financial Card */}
        <ImageBackground style={styles.mainBalanceCard} imageStyle={{ borderRadius: 24 }}>
          <View style={styles.cardOverlay}>
            <Text style={styles.balanceLabel}>TOTAL PENDAPATAN</Text>
            <Text style={styles.balanceValue}>Rp {formatRupiah(stats.total_pendapatan)}</Text>
            <View style={styles.balanceFooter}>
              <View style={styles.trendBox}>
                <Ionicons name="trending-up" size={16} color="#2ecc71" />
                <Text style={styles.trendText}>Live Update</Text>
              </View>
              <Text style={styles.cardSubText}>Kiwari Farm Digital ID</Text>
            </View>
          </View>
        </ImageBackground>

        {/* Stats Grid */}
        <View style={styles.statsGrid}>
          <TouchableOpacity onPress={() => router.push('/owner/karyawan')} style={styles.gridItem}>
            <View style={[styles.iconCircle, { backgroundColor: '#e8f6f3' }]}>
              <Ionicons name="people" size={22} color="#117a65" />
            </View>
            <Text style={styles.gridValue}>{stats.pekerja_aktif}</Text>
            <Text style={styles.gridLabel}>Pekerja Aktif</Text>
          </TouchableOpacity>

          <TouchableOpacity onPress={() => router.push('/owner/laporan')} style={styles.gridItem}>
            <View style={[styles.iconCircle, { backgroundColor: '#fef5e7' }]}>
              <MaterialCommunityIcons name="chart-bell-curve-cumulative" size={22} color="#f39c12" />
            </View>
            <Text style={styles.gridValue}></Text>
            <Text style={styles.gridLabel}>Laporan Baru</Text>
          </TouchableOpacity>

          <View style={styles.gridItem}>
            <View style={[styles.iconCircle, { backgroundColor: '#fdf2f2' }]}>
              <FontAwesome5 name="warehouse" size={18} color="#e74c3c" />
            </View>
            <Text style={styles.gridValue}>85%</Text>
            <Text style={styles.gridLabel}>Stok Gudang</Text>
          </View>
        </View>

        {/* LINE CHART SECTION */}
        <View style={styles.sectionTitleRow}>
          <Text style={styles.sectionTitle}>Business Analytics</Text>
          <TouchableOpacity onPress={fetchDashboardData}>
            <Ionicons name="refresh" size={18} color="#117a65" />
          </TouchableOpacity>
        </View>

        <View style={styles.analyticsCard}>
          <View style={styles.legendContainer}>
            <View style={styles.legendItem}>
              <View style={[styles.legendDot, { backgroundColor: '#0B7A65' }]} />
              <Text style={styles.legendText}>Pemasukan</Text>
            </View>
            <View style={styles.legendItem}>
              <View style={[styles.legendDot, { backgroundColor: '#F1C40F' }]} />
              <Text style={styles.legendText}>Pengeluaran</Text>
            </View>
          </View>

          {stats.chart_data && stats.chart_data.length > 0 ? (
  <LineChart
    data={{
      labels: stats.chart_data.map((d: any) => d.day),
      datasets: [
        {
          data: stats.chart_data.map((d: any) => d.pemasukan),
          color: (opacity = 1) => `rgba(11, 122, 101, ${opacity})`,
          strokeWidth: 3
        },
        {
          data: stats.chart_data.map((d: any) => d.pengeluaran),
          color: (opacity = 1) => `rgba(241, 196, 15, ${opacity})`,
          strokeWidth: 3
        }
      ]
    }}
    width={width - 40} 
    height={220}
    yAxisLabel=""
    yAxisSuffix=""
    // yAxisInterval={1}
    // Sembunyikan label angka di pinggir (Sumbu Y)
    withHorizontalLabels={false} 
    // Tetap tampilkan label bulan di bawah (Sumbu X)
    withVerticalLabels={true} 
    chartConfig={{
      backgroundColor: "#fff",
      backgroundGradientFrom: "#fff",
      backgroundGradientTo: "#fff",
      decimalPlaces: 0,
      color: (opacity = 1) => `rgba(0, 0, 0, ${opacity})`,
      labelColor: (opacity = 1) => `rgba(100, 100, 100, ${opacity})`,
      propsForDots: { r: "4", strokeWidth: "2", stroke: "#fff" },
      fillShadowGradientFrom: "#0B7A65",
      fillShadowGradientTo: "#fff",
      fillShadowGradientFromOpacity: 0.2,
      fillShadowGradientToOpacity: 0,
    }}
    bezier
    style={{
      marginVertical: 8,
      borderRadius: 16,
      paddingRight: 20, 
      paddingLeft: 20 // Padding disesuaikan agar grafik simetris di tengah
    }}
    withInnerLines={false}
    withOuterLines={false}
    segments={5}
  />

          ) : (
            <ActivityIndicator color="#0B7A65" />
          )}
        </View>

        <View style={{ height: 100 }} />
      </ScrollView>
      <BottomNav activeScreen="Home" onNavPress={handleNavigation} />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F0F3F5' },
  scrollContent: { padding: 20 },
  topSection: { marginTop: Platform.OS === 'android' ? 10 : 0, marginBottom: 25 },
  headerRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  welcomeTitle: { fontSize: 14, color: '#7f8c8d' },
  ownerName: { fontSize: 22, fontWeight: 'bold', color: '#2c3e50' },
  profileBadge: { borderWidth: 2, borderColor: '#fff', borderRadius: 15 },
  avatarImg: { width: 45, height: 45, borderRadius: 12 },
  mainBalanceCard: { backgroundColor: '#0e6251', borderRadius: 24, padding: 24, marginBottom: 25, elevation: 8 },
  cardOverlay: { padding: 0 },
  balanceLabel: { color: '#d1f2eb', fontSize: 12, letterSpacing: 1.5, fontWeight: '600' },
  balanceValue: { color: '#fff', fontSize: 28, fontWeight: 'bold', marginVertical: 15 },
  balanceFooter: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  trendBox: { flexDirection: 'row', alignItems: 'center', backgroundColor: 'rgba(46, 204, 113, 0.2)', paddingHorizontal: 8, paddingVertical: 4, borderRadius: 8 },
  trendText: { color: '#2ecc71', fontSize: 11, fontWeight: '600', marginLeft: 4 },
  cardSubText: { color: 'rgba(255,255,255,0.6)', fontSize: 10 },
  statsGrid: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 25 },
  gridItem: { width: (width - 60) / 3, backgroundColor: '#fff', borderRadius: 20, padding: 15, alignItems: 'center', elevation: 2 },
  iconCircle: { padding: 10, borderRadius: 14, marginBottom: 10 },
  gridValue: { fontSize: 18, fontWeight: 'bold', color: '#2c3e50' },
  gridLabel: { fontSize: 10, color: '#95a5a6', textAlign: 'center' },
  sectionTitleRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 },
  sectionTitle: { fontSize: 18, fontWeight: 'bold', color: '#2c3e50' },
  analyticsCard: { backgroundColor: '#fff', borderRadius: 24, padding: 15, marginBottom: 25, elevation: 2 },
  legendContainer: { flexDirection: 'row', justifyContent: 'center', marginBottom: 10 },
  legendItem: { flexDirection: 'row', alignItems: 'center', marginHorizontal: 10 },
  legendDot: { width: 10, height: 10, borderRadius: 5, marginRight: 5 },
  legendText: { fontSize: 12, color: '#7f8c8d', fontWeight: 'bold' },
  monitorCard: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#fff', borderRadius: 18, padding: 15, marginBottom: 12 },
  monitorIconBox: { backgroundColor: '#e8f6f3', padding: 12, borderRadius: 14 },
  monitorInfo: { flex: 1, marginLeft: 15 },
  monitorTitle: { fontSize: 14, fontWeight: 'bold', color: '#2c3e50' },
  monitorStatus: { fontSize: 11, color: '#95a5a6' },
  actionBadge: { backgroundColor: '#117a65', paddingHorizontal: 12, paddingVertical: 6, borderRadius: 10 },
  badgeTxt: { color: '#fff', fontSize: 10, fontWeight: 'bold' },
});