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

export default function FormAbsensiScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(false);
  const [permission, requestPermission] = useCameraPermissions();
  const [isCameraOpen, setIsCameraOpen] = useState(false);
  const [photoBase64, setPhotoBase64] = useState<string | null>(null);
  const cameraRef = useRef<any>(null);

  const [userId, setUserId] = useState<number | null>(null);
  const [employeeName, setEmployeeName] = useState<string>('Memuat...');
  const [currentDateTimeStr, setCurrentDateTimeStr] = useState(''); // Untuk Tampilan User
  const [waktuKunciBackend, setWaktuKunciBackend] = useState(''); // Untuk Database

  const [jenisAbsen, setJenisAbsen] = useState<'absen_datang' | 'tidak_hadir'>('absen_datang');
  const [lokasi, setLokasi] = useState<'kebun_lanud' | 'kebun_sadang'>('kebun_lanud');

  useEffect(() => {
    loadUserSession();
    lockDateTimeOnOpen(); // Kunci waktu saat komponen pertama kali dirender
    if (!permission?.granted) {
      requestPermission();
    }
  }, []);

  const loadUserSession = async () => {
    const jsonValue = await AsyncStorage.getItem('user_session');
    if (jsonValue != null) {
      const sessionData = JSON.parse(jsonValue);
      setUserId(sessionData.user?.id_user || null);
      setEmployeeName(sessionData.user?.nama || 'Karyawan');
    }
  };

  /**
   * Mengunci waktu saat halaman dibuka
   */
  const lockDateTimeOnOpen = () => {
    const now = new Date();
    
    // Format Manual YYYY-MM-DD HH:mm:ss
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
      const response = await fetch('http://10.0.2.2:8000/api/add-absensi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          id_user: userId,
          status: jenisAbsen,
          lokasi: lokasi,
          tanggal_datang: waktuKunciBackend, // Mengirim waktu yang sudah dikunci
          image: photoBase64, 
        }),
      });

      const data = await response.json();
      if (response.ok && data.success) {
        Alert.alert('Sukses', 'Absensi berhasil!', [{ text: 'OK', onPress: () => router.replace('/karyawan/Absensi') }]);
      } else {
        Alert.alert('Gagal', data.message || 'Anda sudah absen hari ini.');
      }
    } catch (e) {
      Alert.alert('Eror', 'Koneksi server gagal');
    } finally {
      setLoading(false);
    }
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
        <TouchableOpacity onPress={() => router.back()}><Ionicons name="chevron-back" size={24} color="#117a65" /></TouchableOpacity>
        <Text style={styles.headerTitle}>Absensi Datang</Text>
      </View>

      <ScrollView contentContainerStyle={styles.scrollPadding}> 
        <View style={styles.infoCard}>
            <Text style={styles.fieldLabel}>Employee Name</Text>
            <View style={styles.readOnlyInput}><Text style={styles.readOnlyText}>{employeeName}</Text></View>
            
            <Text style={[styles.fieldLabel, {marginTop: 10}]}>Waktu Terkunci (Buka Halaman)</Text>
            <View style={styles.readOnlyInput}><Text style={styles.readOnlyText}>{currentDateTimeStr}</Text></View>
        </View>

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

        <View style={styles.inputGroup}>
          <Text style={styles.fieldLabelActive}>Jenis Absensi</Text>
          <View style={styles.pickerWrapper}>
            <Picker selectedValue={jenisAbsen} onValueChange={(item) => setJenisAbsen(item)}>
              <Picker.Item label="Absen Masuk" value="absen_datang" />
              {/* <Picker.Item label="Lembur Datang" value="lembur_datang" /> */}
              <Picker.Item label="Tidak Hadir" value="tidak_hadir" />
            </Picker>
          </View>
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.fieldLabelActive}>Lokasi Kerja</Text>
          <View style={styles.pickerWrapper}>
            <Picker selectedValue={lokasi} onValueChange={(item) => setLokasi(item)}>
              <Picker.Item label="Kebun Lanud" value="kebun_lanud" />
              <Picker.Item label="Kebun Sadang" value="kebun_sadang" />
            </Picker>
          </View>
        </View>

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
                <Text style={styles.submitButtonText}>Submit Absensi</Text>
            </View>
          )}
        </TouchableOpacity>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  header: { flexDirection: 'row', alignItems: 'center', padding: 16, backgroundColor: '#fff', borderBottomWidth: 1, borderColor: '#eaeaea' },
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#117a65', marginLeft: 10 },
  scrollPadding: { padding: 20 },
  infoCard: { backgroundColor: '#fff', padding: 15, borderRadius: 15, marginBottom: 20, elevation: 2 },
  fieldLabel: { fontSize: 12, color: '#7f8c8d', fontWeight: '600' },
  fieldLabelActive: { fontSize: 13, color: '#117a65', fontWeight: 'bold', marginBottom: 8 },
  readOnlyInput: { backgroundColor: '#f1f2f6', padding: 12, borderRadius: 10, marginTop: 5 },
  readOnlyText: { color: '#2f3542', fontSize: 14 },
  inputGroup: { marginBottom: 20 },
  pickerWrapper: { backgroundColor: '#fff', borderWidth: 1, borderColor: '#dcdde1', borderRadius: 12, overflow: 'hidden' },
  cameraTrigger: { height: 160, borderWidth: 2, borderStyle: 'dashed', borderColor: '#117a65', borderRadius: 20, justifyContent: 'center', alignItems: 'center', backgroundColor: '#f0f9f7' },
  previewBox: { alignItems: 'center', backgroundColor: '#fff', padding: 10, borderRadius: 20, borderWidth: 1, borderColor: '#e0e0e0' },
  previewImg: { width: '100%', height: 250, borderRadius: 15 },
  reTakeBtn: { backgroundColor: '#e67e22', paddingHorizontal: 20, paddingVertical: 10, borderRadius: 10, marginTop: 12, flexDirection: 'row', alignItems: 'center' },
  cameraOverlay: { flex: 1, justifyContent: 'flex-end', alignItems: 'center', paddingBottom: 60 },
  captureBtn: { width: 80, height: 80, borderRadius: 40, backgroundColor: 'white', borderWidth: 6, borderColor: 'rgba(255,255,255,0.3)' },
  closeCam: { position: 'absolute', top: 50, right: 25 },
  submitButton: { backgroundColor: '#0a6847', height: 60, borderRadius: 18, justifyContent: 'center', alignItems: 'center', marginTop: 10 },
  submitButtonText: { color: '#fff', fontSize: 17, fontWeight: 'bold' },
});