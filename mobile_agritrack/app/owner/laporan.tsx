import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, ActivityIndicator, Alert } from 'react-native';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import BottomNav from './components/BottomNav';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage'; // Import ini
import * as FileSystem from 'expo-file-system/legacy';
import * as Sharing from 'expo-sharing'; // Library baru

export default function LaporanScreen() {
  const router = useRouter();
  const [laporans, setLaporans] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => { fetchLaporans(); }, []);

  const fetchLaporans = async () => {
    try {
      // Ambil token dari storage
      const token = await AsyncStorage.getItem('userToken');
      const response = await fetch(`http://10.0.2.2:8000/api/owner/laporan-list`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await response.json();
      setLaporans(data);
    } catch (e) { 
      console.error(e); 
    } finally { 
      setLoading(false); 
    }
  };

  const handleDownload = async () => {
  try {
    const sessionData = await AsyncStorage.getItem('user_session');
    if (!sessionData) return Alert.alert("Error", "Sesi tidak ditemukan");

    const parsedData = JSON.parse(sessionData);
    const token = parsedData.token; // Sekarang ini sudah ada isinya!

    if (!token) {
      Alert.alert("Error", "Backend belum mengirimkan token. Login ulang!");
      return;
    }

    const fileUri = FileSystem.documentDirectory + "Laporan.pdf";
    const downloadRes = await FileSystem.downloadAsync(
      `http://10.0.2.2:8000/api/mobile/cashflow/pdf`,
      fileUri,
      { headers: { 'Authorization': `Bearer ${token}` } }
    );

    if (downloadRes.status === 200) {
      await Sharing.shareAsync(fileUri);
    } else {
      Alert.alert("Gagal", "Pastikan route di Laravel sudah benar.");
    }
  } catch (error) {
    console.error(error);
  }
};

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Arsip Laporan</Text>
      </View>
      
      <ScrollView contentContainerStyle={styles.listContainer}>
        {loading ? (
          <ActivityIndicator size="large" color="#3498db" />
        ) : (
          laporans.map((item: any) => (
            <TouchableOpacity key={item.id} style={styles.card} onPress={handleDownload}>
              <MaterialCommunityIcons name="file-pdf-box" size={30} color="#e74c3c" />
              <View style={{ flex: 1, marginLeft: 15 }}>
                <Text style={{ fontWeight: 'bold' }}>{item.judul}</Text>
                <Text style={{ fontSize: 11, color: 'gray' }}>{item.tanggal_buat}</Text>
              </View>
              <MaterialCommunityIcons name="download" size={24} color="#bdc3c7" />
            </TouchableOpacity>
          ))
        )}
      </ScrollView>

      <BottomNav activeScreen="Laporan" 
       onNavPress={(screen) => {
          if (screen === 'Pengeluaran') router.push('/owner/pengeluaran');
          else if (screen === 'Home') router.push('/owner');
          else if (screen === 'Karyawan') router.push('/owner/karyawan');
          else if (screen === 'Laporan') router.push('/owner/laporan');
          else if (screen === 'Profile') router.push('/owner/profile');
          
        }}/>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F0F3F5' },
  header: { padding: 25, backgroundColor: '#fff', borderBottomRightRadius: 30 },
  headerTitle: { fontSize: 24, fontWeight: 'bold' },
  listContainer: { padding: 20 },
  card: { backgroundColor: '#fff', padding: 15, borderRadius: 15, flexDirection: 'row', alignItems: 'center', marginBottom: 10 }
});