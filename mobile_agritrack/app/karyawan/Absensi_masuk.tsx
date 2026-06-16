import React, { useState, useEffect, useRef } from 'react';
import {
  StyleSheet, Text, View, TouchableOpacity, SafeAreaView, 
  ScrollView, StatusBar, Alert, ActivityIndicator, Image
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { CameraView, useCameraPermissions } from 'expo-camera'; 
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { Picker } from '@react-native-picker/picker';
import TrackingStep from './components/TrackingStep'; // Mengembalikan komponen step visual

export default function DashboardKaryawan() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(false);
  const [permission, requestPermission] = useCameraPermissions();
  const [isCameraOpen, setIsCameraOpen] = useState(false);
  const [photoBase64, setPhotoBase64] = useState<string | null>(null);
  const cameraRef = useRef<any>(null);

  // State Session & Waktu
  const [userId, setUserId] = useState<number | null>(null);
  const [employeeName, setEmployeeName] = useState<string>('Memuat...');
  const [currentDateTimeStr, setCurrentDateTimeStr] = useState(''); 
  const [waktuKunciBackend, setWaktuKunciBackend] = useState(''); 

  // State Input Data (Sesuai dengan Form Dropdown kamu)
  const [jenisAbsen, setJenisAbsen] = useState<'absen_datang' | 'tidak_hadir'>('absen_datang');
  const [lokasi, setLokasi] = useState<'pulo' | 'sindang'>('pulo');

  // State Alur Penahanan (State Kontrol agar tidak langsung pindah halaman)
  const [hasCheckedIn, setHasCheckedIn] = useState(false);
  const [savedIdAbsensi, setSavedIdAbsensi] = useState<number | null>(null);
  const [savedTanggalDatang, setSavedTanggalDatang] = useState<string>('');

  useEffect(() => {
    loadUserSession();
    lockDateTimeOnOpen(); 
    if (!permission?.granted) {
      requestPermission();
    }
  }, []);

  const loadUserSession = async () => {
    try {
      const jsonValue = await AsyncStorage.getItem('user_session');
      if (jsonValue != null) {
        const sessionData = JSON.parse(jsonValue);
        setUserId(sessionData.user?.id_user || null);
        setEmployeeName(sessionData.user?.nama || 'Karyawan');
      }
    } catch (e) {
      console.log("Gagal memuat sesi user");
    }
  };

  const lockDateTimeOnOpen = () => {
    const now = new Date();
    const pad = (n: number) => n < 10 ? '0' + n : n;
    const formattedDate = 
      now.getFullYear() + "-" + 
      pad(now.getMonth() + 1) + "-" + 
      pad(now.getDate()) + " " + 
      pad(now.getHours()) + ":" + 
      pad(now.getMinutes()) + ":" + 
      pad(now.getSeconds());

    setWaktuKunciBackend(formattedDate);
    setCurrentDateTimeStr(now.toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }));
  };

  const takePicture = async () => {
    if (cameraRef.current) {
      try {
        const data = await cameraRef.current.takePictureAsync({
          quality: 0.4,
          base64: true,
        });
        setPhotoBase64(data.base64);
        setIsCameraOpen(false);
      } catch (err) {
        Alert.alert("Eror", "Gagal mengambil foto.");
      }
    }
  };

  const handleSubmitAttendance = async () => {
    if (!photoBase64 && jenisAbsen !== 'tidak_hadir') {
      Alert.alert('Gagal', 'Wajib ambil foto selfie!');
      return;
    }

    setLoading(true);
    try {
      // Menembak ke endpoint 'absensi' sesuai rute perbaikan API Laravel Anda
      const response = await fetch('http://10.0.2.2:8000/api/absensi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          id_user: userId,
          status: jenisAbsen,
          lokasi: lokasi,
          tanggal_datang: waktuKunciBackend, 
          image: photoBase64, 
        }),
      });

      const data = await response.json();
      
      if (response.ok) {
        // DI SINI KUNCINYA: Tidak mengarahkan router.replace, melainkan mengunci state di dashboard
        setSavedIdAbsensi(data.id_absensi || 1);
        setSavedTanggalDatang(waktuKunciBackend);
        setHasCheckedIn(true);

        Alert.alert('Sukses Masuk', 'Presensi datang berhasil dicatat. Selamat bekerja! Jangan lupa klik tombol "Absen Pulang" jika tugas hari ini selesai.');
      } else {
        Alert.alert('Gagal', data.message || 'Anda sudah absen hari ini.');
      }
    } catch (e) {
      Alert.alert('Eror', 'Gagal menghubungkan ke server lokal Laragon.');
    } finally {
      setLoading(false);
    }
  };

  const handleKeAbsenPulang = () => {
    // Tombol manual ini yang akan memindahkan user ke file owner/data_karyawan atau form logbook
    router.push({
      pathname: '/karyawan/absen_pulang',
      params: { 
        id_absensi: savedIdAbsensi, 
        tanggal_datang: savedTanggalDatang 
      }
    });
  };

  if (isCameraOpen) {
    return (
      <View style={{ flex: 1, backgroundColor: 'black' }}>
        <CameraView style={StyleSheet.absoluteFillObject} facing="front" ref={cameraRef} />
        <View style={styles.cameraOverlay}>
          <TouchableOpacity onPress={() => setIsCameraOpen(false)} style={styles.closeCam}>
            <Ionicons name="close-circle" size={45} color="white" />
          </TouchableOpacity>
          <TouchableOpacity onPress={takePicture} style={styles.captureBtn} />
        </View>
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <View style={styles.header}>
        <Text style={styles.headerTitle}>AgriTrack Dashboard</Text>
      </View>

      <ScrollView contentContainerStyle={styles.scrollPadding}> 
        {/* Indikator Alur Pelacakan: Jika sudah masuk, naikkan visual step ke step 2 */}
        <TrackingStep currentStep={hasCheckedIn ? 2 : 1} />

        <View style={styles.card}>
          <Text style={styles.cardTitle}>
            {hasCheckedIn ? 'Status Kerja Aktif' : 'Formulir Absensi'}
          </Text>
          <Text style={styles.cardSub}>
            {hasCheckedIn 
              ? 'Anda telah melakukan presensi datang hari ini. Silahkan klik tombol di bawah untuk mengakhiri kerja dan mengisi logbook kerja.' 
              : 'Harap lengkapi bukti foto selfie beserta opsi data kehadiran di bawah ini.'}
          </Text>

          <View style={styles.divider} />

          {/* KONDISI 1: JIKA BELUM ABSEN MASUK -> TAMPILKAN INPUT INTEGRAL */}
          {!hasCheckedIn ? (
            <View>
              <View style={styles.infoCard}>
                <Text style={styles.fieldLabel}>Employee Name</Text>
                <View style={styles.readOnlyInput}><Text style={styles.readOnlyText}>{employeeName}</Text></View>
                
                <Text style={[styles.fieldLabel, {marginTop: 10}]}>Waktu Terkunci (Buka Halaman)</Text>
                <View style={styles.readOnlyInput}><Text style={styles.readOnlyText}>{currentDateTimeStr}</Text></View>
              </View>

              {/* BUKTI FOTO */}
              <View style={styles.inputGroup}>
                <Text style={styles.fieldLabelActive}>Bukti Foto Selfie <Text style={{color:'red'}}>*</Text></Text>
                {photoBase64 ? (
                  <View style={styles.previewBox}>
                    <Image source={{ uri: `data:image/png;base64,${photoBase64}` }} style={styles.previewImg} />
                    <TouchableOpacity onPress={() => setIsCameraOpen(true)} style={styles.reTakeBtn}>
                      <Ionicons name="refresh" size={16} color="white" />
                      <Text style={{color: 'white', fontWeight: 'bold', marginLeft: 5}}>Ambil Ulang</Text>
                    </TouchableOpacity>
                  </View>
                ) : (
                  <TouchableOpacity style={styles.cameraTrigger} onPress={() => setIsCameraOpen(true)}>
                    <Ionicons name="camera" size={40} color="#117a65" />
                    <Text style={{color: '#117a65', marginTop: 8, fontWeight: '600'}}>Ambil Foto</Text>
                  </TouchableOpacity>
                )}
              </View>

              {/* PICKER JENIS ABSENSI */}
              <View style={styles.inputGroup}>
                <Text style={styles.fieldLabelActive}>Jenis Absensi</Text>
                <View style={styles.pickerWrapper}>
                  <Picker selectedValue={jenisAbsen} onValueChange={(item) => setJenisAbsen(item)}>
                    <Picker.Item label="Absen Masuk" value="absen_datang" />
                    <Picker.Item label="Tidak Hadir" value="tidak_hadir" />
                  </Picker>
                </View>
              </View>

              {/* PICKER LOKASI KERJA */}
              <View style={styles.inputGroup}>
                <Text style={styles.fieldLabelActive}>Lokasi Kerja</Text>
                <View style={styles.pickerWrapper}>
                  <Picker selectedValue={lokasi} onValueChange={(item) => setLokasi(item)}>
                    <Picker.Item label="pulo" value="pulo" />
                    <Picker.Item label="sindang" value="sindang" />
                  </Picker>
                </View>
              </View>

              <View style={styles.divider} />

              <TouchableOpacity 
                style={[styles.submitButton, loading && { opacity: 0.7 }]} 
                onPress={handleSubmitAttendance} 
                disabled={loading}
              >
                {loading ? (
                  <ActivityIndicator color="#fff" />
                ) : (
                  <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                    <MaterialCommunityIcons name="fingerprint" size={24} color="white" style={{marginRight: 10}} />
                    <Text style={styles.submitButtonText}>Submit Absensi Masuk</Text>
                  </View>
                )}
              </TouchableOpacity>
            </View>
          ) : (
            /* KONDISI 2: JIKA SUDAH ABSEN MASUK -> KUNCI INPUT & TAMPILKAN TOMBOL PULANG MANUAL */
            <View style={{ alignItems: 'center', paddingVertical: 10 }}>
              <View style={styles.infoBadge}>
                <Ionicons name="time-outline" size={16} color="#117a65" />
                <Text style={styles.infoBadgeText}>Waktu Datang Terบันทึก: {savedTanggalDatang}</Text>
              </View>

              <TouchableOpacity style={styles.btnPulang} onPress={handleKeAbsenPulang}>
                <Ionicons name="log-out" size={24} color="#fff" />
                <Text style={styles.btnText}>Absen Pulang & Isi Logbook</Text>
              </TouchableOpacity>
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
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#117a65' },
  scrollPadding: { padding: 20 },
  card: { backgroundColor: '#fff', borderRadius: 16, padding: 20, borderWidth: 1, borderColor: '#eaeaea', marginTop: 10 },
  cardTitle: { fontSize: 18, fontWeight: 'bold', color: '#2c3e50' },
  cardSub: { fontSize: 13, color: '#7f8c8d', marginTop: 4, marginBottom: 15, lineHeight: 18 },
  divider: { height: 1, backgroundColor: '#f0f0f0', marginVertical: 15 },
  infoCard: { backgroundColor: '#f8f9fa', padding: 15, borderRadius: 15, marginBottom: 20, borderWidth: 1, borderColor: '#eef0f2' },
  fieldLabel: { fontSize: 12, color: '#7f8c8d', fontWeight: '600' },
  fieldLabelActive: { fontSize: 13, color: '#117a65', fontWeight: 'bold', marginBottom: 8 },
  readOnlyInput: { backgroundColor: '#f1f2f6', padding: 12, borderRadius: 10, marginTop: 5 },
  readOnlyText: { color: '#2f3542', fontSize: 13, fontWeight: '500' },
  inputGroup: { marginBottom: 20 },
  pickerWrapper: { backgroundColor: '#fff', borderWidth: 1, borderColor: '#dcdde1', borderRadius: 12, overflow: 'hidden' },
  cameraTrigger: { height: 150, borderWidth: 2, borderStyle: 'dashed', borderColor: '#117a65', borderRadius: 20, justifyContent: 'center', alignItems: 'center', backgroundColor: '#f0f9f7' },
  previewBox: { alignItems: 'center', backgroundColor: '#fff', padding: 10, borderRadius: 20, borderWidth: 1, borderColor: '#e0e0e0' },
  previewImg: { width: '100%', height: 230, borderRadius: 15 },
  reTakeBtn: { backgroundColor: '#e67e22', paddingHorizontal: 20, paddingVertical: 10, borderRadius: 10, marginTop: 12, flexDirection: 'row', alignItems: 'center' },
  cameraOverlay: { flex: 1, justifyContent: 'flex-end', alignItems: 'center', paddingBottom: 60 },
  captureBtn: { width: 75, height: 75, borderRadius: 37.5, backgroundColor: 'white', borderWidth: 6, borderColor: 'rgba(255,255,255,0.3)' },
  closeCam: { position: 'absolute', top: 50, right: 25 },
  submitButton: { backgroundColor: '#0a6847', height: 55, borderRadius: 14, justifyContent: 'center', alignItems: 'center', marginTop: 10, width: '100%' },
  submitButtonText: { color: '#fff', fontSize: 16, fontWeight: 'bold' },
  btnPulang: { backgroundColor: '#c0392b', height: 52, borderRadius: 14, flexDirection: 'row', justifyContent: 'center', alignItems: 'center', width: '100%', marginTop: 10 },
  btnText: { color: '#fff', fontWeight: 'bold', marginLeft: 8, fontSize: 15 },
  infoBadge: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#e8f8f5', paddingHorizontal: 14, paddingVertical: 8, borderRadius: 20, marginBottom: 15 },
  infoBadgeText: { color: '#117a65', fontSize: 12, fontWeight: '600', marginLeft: 4 }
});