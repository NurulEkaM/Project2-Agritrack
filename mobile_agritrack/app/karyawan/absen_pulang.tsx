import React, { useState, useEffect } from 'react';
import { 
  StyleSheet, Text, View, TouchableOpacity, SafeAreaView, 
  Alert, TextInput, ActivityIndicator, ScrollView, StatusBar, Platform 
} from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

export default function AbsenPulangScreen() {
  const router = useRouter();
  const { id_absensi, tanggal_datang } = useLocalSearchParams(); 

  const [loading, setLoading] = useState<boolean>(false);
  const [kegiatan, setKegiatan] = useState<string>('');
  
  // STATE UNTUK MENGUNCI WAKTU
  const [waktuPulangKunci, setWaktuPulangKunci] = useState<string>('');
  const [checkInTime, setCheckInTime] = useState<string>('--:--');
  const [currentTime, setCurrentTime] = useState<string>('--:--');
  const [durationStr, setDurationStr] = useState<string>('- Jam');

  useEffect(() => {
    // 1. KUNCI WAKTU PULANG (FREEZE)
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const formattedNow = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    setWaktuPulangKunci(formattedNow);

    // 2. SINKRONISASI TAMPILAN
    if (tanggal_datang) {
      const checkInDate = new Date(String(tanggal_datang).replace(' ', 'T'));
      setCheckInTime(formatDisplayTime(checkInDate));
      setCurrentTime(formatDisplayTime(now));

      const diffMs = now.getTime() - checkInDate.getTime();
      const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
      setDurationStr(diffHours > 0 ? `${diffHours} Jam` : `${Math.floor(diffMs / (1000 * 60))} Menit`);
    }
  }, [tanggal_datang]);

  const formatDisplayTime = (dateObj: Date) => {
    let hours = dateObj.getHours();
    const mins = dateObj.getMinutes().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const displayHours = hours % 12 || 12;
    return `${displayHours.toString().padStart(2, '0')}:${mins} ${ampm}`;
  };

  const handleProcessPulang = async () => {
    if (!kegiatan.trim()) {
      Alert.alert('Peringatan', 'Isi logbook kegiatan hari ini terlebih dahulu!');
      return;
    }

    setLoading(true);
    try {
      const response = await fetch(`http://10.0.2.2:8000/api/absensi-pulang/${id_absensi}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ 
          kegiatan: kegiatan,
          tanggal_pulang: waktuPulangKunci // Mengirim waktu yang dikunci
        })
      });

      const data = await response.json();
      if (response.ok && data.success) {
        Alert.alert('Sukses', `Berhasil absen pulang! Lembur: ${data.lembur} jam`, [
          { text: 'OK', onPress: () => router.replace('/karyawan/Absensi') }
        ]);
      }
    } catch (error) {
      Alert.alert('Eror', 'Terjadi kesalahan koneksi ke server.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()}><Ionicons name="chevron-back" size={24} color="#117a65" /></TouchableOpacity>
        <Text style={styles.headerTitle}>Absensi Pulang</Text>
      </View>

      <ScrollView contentContainerStyle={styles.scrollContent}>
         <Text style={styles.mainTitle}>Konfirmasi Selesai</Text>
        <Text style={styles.subTitle}>Pastikan semua pekerjaan Anda telah tercatat dengan benar.</Text>

        <View style={styles.durationCard}>
          <View style={styles.cardHeaderRow}>
            <Text style={styles.cardLabelText}>DURASI KERJA (TERKUNCI)</Text>
            <View style={styles.durationBadge}><Text style={styles.durationBadgeText}>{durationStr}</Text></View>
          </View>
          <View style={styles.timeSectionRow}>
            <View style={styles.timeColumn}>
              <Text style={styles.timeLabel}>Check-in</Text>
              <Text style={styles.timeValueText}>{checkInTime}</Text>
            </View>
            <View style={styles.dividerDotted} />
            <View style={styles.timeColumn}>
              <Text style={[styles.timeLabel, { textAlign: 'right' }]}>Jam Pulang</Text>
              <Text style={[styles.timeValueText, { color: '#117a65', textAlign: 'right' }]}>{currentTime}</Text>
            </View>
          </View>
        </View>

        <View style={styles.logbookCard}>
          <View style={styles.logbookHeader}>
            <MaterialCommunityIcons name="file-document-edit-outline" size={18} color="#27ae60" />
            <Text style={styles.logbookTitleText}>Logbook (Kegiatan)</Text>
          </View>
          <TextInput
            style={styles.textArea}
            placeholder="Tuliskan kegiatan anda hari ini..."
            multiline
            numberOfLines={6}
            textAlignVertical="top"
            value={kegiatan}
            onChangeText={setKegiatan}
          />
        </View>

        <TouchableOpacity style={styles.btnSubmitPulang} onPress={handleProcessPulang} disabled={loading}>
          {loading ? <ActivityIndicator color="#ffffff" /> : <Text style={styles.btnSubmitText}>Konfirmasi Absen Pulang</Text>}
        </TouchableOpacity>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  header: { flexDirection: 'row', alignItems: 'center', padding: 16, backgroundColor: '#fff', borderBottomWidth: 1, borderColor: '#eaeaea' },
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#117a65', marginLeft: 10 },
  scrollContent: { padding: 20 },
  durationCard: { backgroundColor: '#fff', borderRadius: 16, padding: 18, borderWidth: 1, borderColor: '#eaeaea', marginBottom: 16 },
  cardHeaderRow: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 16 },
  cardLabelText: { fontSize: 11, color: '#7f8c8d', fontWeight: '700' },
  durationBadge: { backgroundColor: '#4ecb80', paddingHorizontal: 12, borderRadius: 12 },
  durationBadgeText: { color: '#fff', fontSize: 11, fontWeight: 'bold' },
  timeSectionRow: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' },
  timeColumn: { flex: 1 },
  timeLabel: { fontSize: 11, color: '#95a5a6' },
  timeValueText: { fontSize: 20, fontWeight: 'bold', color: '#2c3e50' },
  dividerDotted: { width: 1, height: 35, borderWidth: 1, borderColor: '#bdc3c7', borderStyle: 'dashed', marginHorizontal: 15 },
  logbookCard: { backgroundColor: '#fff', borderRadius: 16, padding: 16, borderWidth: 1, borderColor: '#eaeaea', marginBottom: 35 },
  logbookHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 12 },
  logbookTitleText: { fontSize: 13, fontWeight: '700', marginLeft: 6 },
  textArea: { fontSize: 14, minHeight: 120 },
  btnSubmitPulang: { backgroundColor: '#0a6847', height: 52, borderRadius: 14, justifyContent: 'center', alignItems: 'center' },
  btnSubmitText: { color: '#fff', fontSize: 15, fontWeight: 'bold' },
  mainTitle: { fontSize: 22, fontWeight: 'bold', color: '#117a65', marginBottom: 6 },
  subTitle: { fontSize: 14, color: '#7f8c8d', marginBottom: 20 },
});