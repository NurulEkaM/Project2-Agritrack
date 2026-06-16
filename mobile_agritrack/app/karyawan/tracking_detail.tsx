import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Alert, ActivityIndicator, SafeAreaView, ScrollView } from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import TrackingStep from './components/TrackingStep';

export default function TrackingDetail() {
  const router = useRouter();
  // Menerima parameter dari Tahap 2 (Logbook)
  const { id_absensi, tanggal_datang, kegiatan } = useLocalSearchParams();

  const [loading, setLoading] = useState(false);
  const [isLembur, setIsLembur] = useState<boolean | null>(null); // null = belum pilih, true = lembur, false = pulang normal
  const [lemburStarted, setLemburStarted] = useState<boolean>(false); // Melacak apakah lembur sudah dimulai
  const [waktuMulaiLemburStr, setWaktuMulaiLemburStr] = useState<string>('');

  // Fungsi helper format date Y-m-d H:i:s untuk MySQL
  const getFormattedDate = (date: Date) => {
    const pad = (n: number) => n < 10 ? '0' + n : n;
    return date.getFullYear() + "-" + 
           pad(date.getMonth() + 1) + "-" + 
           pad(date.getDate()) + " " + 
           pad(date.getHours()) + ":" + 
           pad(date.getMinutes()) + ":" + 
           pad(date.getSeconds());
  };

  /**
   * KONDISI A: PULANG NORMAL (TIDAK LEMBUR)
   * Langsung hitung jam pulang saat ini dan selesaikan absensi.
   */
  const handlePulangNormal = async () => {
    setLoading(true);
    const now = new Date();
    const tanggalPulang = getFormattedDate(now);

    try {
      const response = await fetch('http://10.0.2.2:8000/api/absensi-pulang/' + id_absensi, { // Sesuaikan endpoint update/pulang Anda
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          id_absensi: id_absensi,
          status: 'selesai', // Tetap status standar
          kegiatan: kegiatan,
          tanggal_pulang: tanggalPulang,
          total_lembur: 0
        }),
      });

      const data = await response.json();
      if (response.ok) {
        Alert.alert('Sukses', 'Absensi pulang normal berhasil dicatat!', [
          { text: 'OK', onPress: () => router.replace('/karyawan/Absensi') }
        ]);
      } else {
        Alert.alert('Gagal', data.message || 'Gagal memperbarui data.');
      }
    } catch (error) {
      Alert.alert('Eror', 'Koneksi server gagal.');
    } finally {
      setLoading(false);
    }
  };

  /**
   * KONDISI B1: MULAI LEMBUR
   * Set tanggal_pulang jadi NULL di backend, status berubah jadi 'lembur', tahan user di Tahap 3.
   */
  const handleMulaiLembur = async () => {
    setLoading(true);
    const now = new Date();
    setWaktuMulaiLemburStr(now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }));

    try {
      const response = await fetch('http://10.0.2.2:8000/api/absensi-pulang/' + id_absensi, { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          id_absensi: id_absensi,
          status: 'lembur', // Berubah jadi lembur agar ENUM database sinkron (Menghindari Error 1265)
          kegiatan: kegiatan,
          tanggal_pulang: null, // Sesuai instruksi: Tanggal pulang diset null saat lembur berjalan
          total_lembur: 0
        }),
      });

      const data = await response.json();
      if (response.ok) {
        setLemburStarted(true); // Kunci tampilan agar tetap di Tahap 3 dengan opsi "Selesai Lembur"
        Alert.alert('Lembur Dimulai', 'Status Anda kini sedang Lembur. Selamat melanjutkan pekerjaan.');
      } else {
        Alert.alert('Gagal', data.message || 'Gagal memperbarui status lembur.');
      }
    } catch (error) {
      Alert.alert('Eror', 'Koneksi server gagal.');
    } finally {
      setLoading(false);
    }
  };

  /**
   * KONDISI B2: SELESAI LEMBUR
   * Hitung selisih jam dari batas 16:00 sore, lalu update tanggal_pulang & total_lembur riil.
   */
  const handleSelesaiLembur = async () => {
    setLoading(true);
    const now = new Date();
    const tanggalPulangRiil = getFormattedDate(now);

    // --- LOGIKA HITUNG TIAP JAM MELEBIHI JAM 16:00 ---
    const batasJamPulang = new Date(now);
    batasJamPulang.setHours(16, 0, 0, 0); // Di-set ke jam 16:00 hari ini

    let hitungLembur = 0;

    if (now > batasJamPulang) {
      // Hitung selisih milidetik lalu ubah ke satuan jam
      const selisihMilidetik = now.getTime() - batasJamPulang.getTime();
      const selisihJam = selisihMilidetik / (1000 * 60 * 60);
      
      // Menggunakan Math.floor (pembulatan ke bawah per jam genap) atau Math.round tergantung kebijakan Anda
      hitungLembur = Math.floor(selisihJam); 
      
      // Antisipasi jika belum genap 1 jam lembur tapi sudah klik selesai lembur
      if (hitungLembur < 0) hitungLembur = 0;
    }

    try {
      const response = await fetch('http://10.0.2.2:8000/api/absensi-pulang/' + id_absensi, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          id_absensi: id_absensi,
          status: 'lembur', 
          kegiatan: kegiatan,
          tanggal_pulang: tanggalPulangRiil, // Mengisi nilai asli jam pulang lembur
          total_lembur: hitungLembur // Mengirim kalkulasi angka integer lembur (Misal: 1, 2, 3...)
        }),
      });

      const data = await response.json();
      if (response.ok) {
        Alert.alert('Sukses Lembur', `Lembur selesai! Anda mendapatkan total akumulasi ${hitungLembur} Jam Lembur hari ini.`, [
          { text: 'Selesai', onPress: () => router.replace('/karyawan/Absensi') }
        ]);
      } else {
        Alert.alert('Gagal', data.message || 'Gagal menyimpan kalkulasi lembur.');
      }
    } catch (error) {
      Alert.alert('Eror', 'Koneksi server gagal.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Konfirmasi Akhir Kerja (Tahap 3)</Text>
      </View>

      <ScrollView contentContainerStyle={{ padding: 20 }}>
        {/* Tetap berada di grafik Step 3 */}
        <TrackingStep currentStep={3} />

        <View style={styles.card}>
          <Text style={styles.cardTitle}>Apakah Anda akan mengambil/mengklaim Lembur hari ini?</Text>
          <Text style={styles.cardSub}>Jika tidak, pilih Pulang Normal untuk langsung menutup absensi.</Text>

          {/* JIKA LEMBUR BELUM DIMULAI -> TAMPILKAN OPSI PILIHAN UTAMA */}
          {!lemburStarted ? (
            <View style={{ marginTop: 15 }}>
              <TouchableOpacity 
                style={[styles.choiceBtn, isLembur === false && styles.choiceActiveNormal]}
                onPress={() => setIsLembur(false)}
              >
                <Ionicons name="home" size={22} color={isLembur === false ? '#fff' : '#27ae60'} />
                <Text style={[styles.choiceText, isLembur === false && styles.choiceTextActive]}>Tidak, Pulang Normal</Text>
              </TouchableOpacity>

              <TouchableOpacity 
                style={[styles.choiceBtn, isLembur === true && styles.choiceActiveLembur]}
                onPress={() => setIsLembur(true)}
              >
                <Ionicons name="time" size={22} color={isLembur === true ? '#fff' : '#e67e22'} />
                <Text style={[styles.choiceText, isLembur === true && styles.choiceTextActive]}>Ya, Saya Lembur</Text>
              </TouchableOpacity>

              <View style={styles.divider} />

              {/* ACTION BUTTON MENYESUAIKAN PILIHAN */}
              {loading ? (
                <ActivityIndicator size="large" color="#117a65" />
              ) : (
                isLembur !== null && (
                  <TouchableOpacity 
                    style={[styles.submitBtn, isLembur ? { backgroundColor: '#e67e22' } : { backgroundColor: '#27ae60' }]}
                    onPress={isLembur ? handleMulaiLembur : handlePulangNormal}
                  >
                    <Text style={styles.submitBtnText}>
                      {isLembur ? 'Konfirmasi Mulai Lembur' : 'Selesai & Kirim Absensi'}
                    </Text>
                  </TouchableOpacity>
                )
              )
              }
            </View>
          ) : (
            /* JIKA LEMBUR SUDAH AKTIF -> LOCK DI SINI, USER WAJIB KLIK SELESAI LEMBUR */
            <View style={{ alignItems: 'center', marginTop: 15 }}>
              <View style={styles.alertBadge}>
                <MaterialCommunityIcons name="alert-decagram" size={20} color="#e67e22" />
                <Text style={styles.alertBadgeText}>Status: Lembur Berjalan sejak {waktuMulaiLemburStr}</Text>
              </View>
              <Text style={styles.noticeText}>
                Sistem mengunci pengisian tanggal pulang Anda menjadi <Text style={{fontWeight:'bold'}}>KOSONG</Text>. Klik tombol di bawah jika pengerjaan lembur benar-benar telah selesai untuk menghitung kalkulasi jam (Batas hitung &gt; 20:00).
              </Text>

              {loading ? (
                <ActivityIndicator size="large" color="#e67e22" style={{ marginTop: 15 }} />
              ) : (
                <TouchableOpacity style={styles.btnSelesaiLembur} onPress={handleSelesaiLembur}>
                  <MaterialCommunityIcons name="clock-check" size={24} color="#fff" />
                  <Text style={styles.submitBtnText}>Selesai Lembur & Hitung Jam</Text>
                </TouchableOpacity>
              )}
            </View>
          )}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  header: { padding: 16, backgroundColor: '#fff', borderBottomWidth: 1, borderColor: '#eaeaea', alignItems: 'center' },
  headerTitle: { fontSize: 16, fontWeight: 'bold', color: '#117a65' },
  card: { backgroundColor: '#fff', borderRadius: 16, padding: 20, borderWidth: 1, borderColor: '#eaeaea', marginTop: 15 },
  cardTitle: { fontSize: 16, fontWeight: 'bold', color: '#2c3e50', textAlign: 'center', lineHeight: 22 },
  cardSub: { fontSize: 12, color: '#7f8c8d', marginTop: 6, textAlign: 'center' },
  choiceBtn: { flexDirection: 'row', alignItems: 'center', padding: 14, borderWidth: 1, borderColor: '#bdc3c7', borderRadius: 12, marginTop: 12 },
  choiceActiveNormal: { backgroundColor: '#27ae60', borderColor: '#27ae60' },
  choiceActiveLembur: { backgroundColor: '#e67e22', borderColor: '#e67e22' },
  choiceText: { fontSize: 14, fontWeight: '600', marginLeft: 10, color: '#515b60' },
  choiceTextActive: { color: '#fff', fontWeight: 'bold' },
  divider: { height: 1, backgroundColor: '#f0f0f0', marginVertical: 20 },
  submitBtn: { height: 50, borderRadius: 12, justifyContent: 'center', alignItems: 'center' },
  submitBtnText: { color: '#fff', fontSize: 15, fontWeight: 'bold', marginLeft: 8 },
  alertBadge: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#fdf2e9', paddingHorizontal: 12, paddingVertical: 8, borderRadius: 20, marginBottom: 12 },
  alertBadgeText: { color: '#e67e22', fontSize: 12, fontWeight: '700', marginLeft: 5 },
  noticeText: { fontSize: 13, color: '#7f8c8d', textAlign: 'center', lineHeight: 18, marginBottom: 20 },
  btnSelesaiLembur: { backgroundColor: '#d35400', height: 52, borderRadius: 12, flexDirection: 'row', justifyContent: 'center', alignItems: 'center', width: '100%' }
});