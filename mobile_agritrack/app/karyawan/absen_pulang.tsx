import React, { useState } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, SafeAreaView, TextInput, Alert, ScrollView } from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import TrackingStep from './components/TrackingStep';

export default function AbsenPulang() {
  const router = useRouter();
  const { id_absensi, tanggal_datang } = useLocalSearchParams(); 
  const [kegiatan, setKegiatan] = useState('');

  const handleNextToTracking = () => {
    if (!kegiatan.trim()) {
      Alert.alert('Peringatan', 'Anda wajib mengisi logbook kegiatan terlebih dahulu!');
      return;
    }
    
    // Melempar parameter data logbook ke step berikutnya
    router.push({
      pathname: '/karyawan/tracking_detail',
      params: { id_absensi, tanggal_datang, kegiatan }
    });
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity style={styles.backButton} onPress={() => router.replace('/karyawan/Absensi')}>
          <Ionicons name="chevron-back" size={24} color="#117a65" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Absen Pulang (Tahap 2)</Text>
      </View>

      <ScrollView contentContainerStyle={{ padding: 20 }}>
        {/* Indikator berada di Step 2 */}
        <TrackingStep currentStep={2} />

        <Text style={styles.title}>Silahkan Isi Logbook Kegiatan</Text>
        <Text style={styles.subtitle}>Tuliskan progress kerjaan yang Anda selesaikan hari ini.</Text>

        <View style={styles.card}>
          <TextInput
            style={styles.inputArea}
            placeholder="Contoh: Pemeliharaan server basis data terdistribusi, perbaikan bug koneksi API lokal..."
            multiline
            numberOfLines={6}
            textAlignVertical="top"
            value={kegiatan}
            onChangeText={setKegiatan}
          />
        </View>

        <TouchableOpacity style={styles.btnNext} onPress={handleNextToTracking}>
          <Text style={styles.btnText}>Lanjut ke Tahap Lembur (Step 3)</Text>
          <Ionicons name="arrow-forward" size={18} color="#fff" />
        </TouchableOpacity>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  header: { flexDirection: 'row', alignItems: 'center', padding: 16, backgroundColor: '#fff', borderBottomWidth: 1, borderColor: '#eaeaea' },
  backButton: { paddingRight: 8 },
  headerTitle: { fontSize: 16, fontWeight: 'bold', color: '#117a65' },
  title: { fontSize: 18, fontWeight: 'bold', color: '#2c3e50', marginBottom: 4 },
  subtitle: { fontSize: 13, color: '#7f8c8d', marginBottom: 20 },
  card: { backgroundColor: '#fff', borderRadius: 14, padding: 16, borderWidth: 1, borderColor: '#eaeaea', marginBottom: 20 },
  inputArea: { fontSize: 14, minHeight: 120, color: '#333' },
  btnNext: { backgroundColor: '#117a65', height: 48, borderRadius: 12, flexDirection: 'row', justifyContent: 'center', alignItems: 'center' },
  btnText: { color: '#fff', fontWeight: 'bold', marginRight: 8 },
});