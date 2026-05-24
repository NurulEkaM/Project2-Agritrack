import React, { useState, useEffect } from 'react';
import {
  StyleSheet, Text, View, TextInput, TouchableOpacity,
  SafeAreaView, ScrollView, StatusBar, Platform, Alert, ActivityIndicator
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';

export default function FormAbsensiScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState<boolean>(false);
  
  // State Data User & Waktu
  const [userId, setUserId] = useState<number | null>(null);
  const [employeeName, setEmployeeName] = useState<string>('Memuat...');
  const [jobTitle, setJobTitle] = useState<string>('Farm Supervisor'); 
  const [currentDateTimeStr, setCurrentDateTimeStr] = useState<string>(''); // Untuk Tampilan UI
  const [waktuKunciBackend, setWaktuKunciBackend] = useState<string>(''); // UNTUK DIKIRIM KE DATABASE

  // State Form Input Data
  const [jenisAbsen, setJenisAbsen] = useState<'absen_datang' | 'lembur_datang' | 'tidak_hadir'>('absen_datang');
  const [lokasi, setLokasi] = useState<'kebun_lanud' | 'kebun_sadang'>('kebun_lanud'); 
  
  const [showDropdownAbsen, setShowDropdownAbsen] = useState<boolean>(false);
  const [showDropdownLokasi, setShowDropdownLokasi] = useState<boolean>(false);

  useEffect(() => {
    loadUserSession();
    lockDateTimeOnOpen(); // Kunci waktu saat komponen pertama kali dibuka
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
      console.error("Gagal memuat sesi user:", e);
    }
  };

  // FUNGSI UNTUK MENGUNCI WAKTU (FREEZE TIME)
  const lockDateTimeOnOpen = () => {
    const months = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    const now = new Date();
    
    // 1. Format untuk Tampilan UI (Readable)
    const day = now.getDate().toString().padStart(2, '0');
    const monthName = months[now.getMonth()];
    const year = now.getFullYear();
    let hoursDisplay = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const ampm = hoursDisplay >= 12 ? 'PM' : 'AM';
    hoursDisplay = hoursDisplay % 12 || 12;
    const hoursStr = hoursDisplay.toString().padStart(2, '0');
    setCurrentDateTimeStr(`${monthName} ${day}, ${year} — ${hoursStr}:${minutes} ${ampm}`);

    // 2. Format untuk Backend (YYYY-MM-DD HH:mm:ss)
    const monthNum = String(now.getMonth() + 1).padStart(2, '0');
    const hours24 = String(now.getHours()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    setWaktuKunciBackend(`${year}-${monthNum}-${day} ${hours24}:${minutes}:${seconds}`);
  };

  const handleSubmitAttendance = async () => {
    if (!userId) {
      Alert.alert('Eror', 'ID Pengguna tidak ditemukan. Silakan login ulang.');
      return;
    }

    setLoading(true);
    try {
      const API_URL = 'http://10.0.2.2:8000/api/add-absensi'; 

      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          id_user: userId,
          status: jenisAbsen,
          lokasi: lokasi,
          tanggal_datang: waktuKunciBackend // MENGIRIM WAKTU YANG SUDAH DIKUNCI
        }),
      });

      const data = await response.json();

      if (response.ok && data.success) {
        Alert.alert('Sukses', 'Absensi berhasil dikirim!', [
          { text: 'OK', onPress: () => router.replace('/karyawan/Absensi') }
        ]);
      } else {
        Alert.alert('Gagal', data.message || 'Terjadi kesalahan pada server.');
      }
    } catch (error) {
      Alert.alert('Eror', 'Tidak dapat terhubung ke server backend.');
    } finally {
      setLoading(false);
    }
  };

  const getDropdownLabel = (value: string) => {
    if (value === 'absen_datang') return 'Absen Masuk';
    if (value === 'lembur_datang') return 'Lembur Datang';
    return 'Tidak Hadir';
  };

  const getLokasiLabel = (value: string) => {
    if (value === 'kebun_lanud') return 'Kebun Lanud';
    return 'Kebun Sadang';
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#f8f9fa" />
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backButton}>
          <Ionicons name="chevron-back" size={24} color="#117a65" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Absensi</Text>
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollPadding}>
        <View style={styles.greenBanner}>
          <Text style={styles.bannerTitle}>Attendance Form</Text>
          <Text style={styles.bannerSubtitle}>Please record your shift activities for today.</Text>
        </View>

        <View style={styles.infoCard}>
          <View style={styles.inputGroup}>
            <Text style={styles.fieldLabel}>Employee Name</Text>
            <View style={styles.readOnlyInput}>
              <Ionicons name="person-outline" size={16} color="#117a65" style={styles.fieldIcon} />
              <Text style={styles.readOnlyText}>{employeeName}</Text>
            </View>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.fieldLabel}>Date and Time (Locked)</Text>
            <View style={styles.readOnlyInput}>
              <Ionicons name="time-outline" size={16} color="#117a65" style={styles.fieldIcon} />
              <Text style={styles.readOnlyText}>{currentDateTimeStr}</Text>
            </View>
          </View>
        </View>

        {/* DROPDOWN JENIS ABSENSI */}
        <View style={styles.inputGroup}>
          <Text style={styles.fieldLabelActive}>Jenis Absensi <Text style={styles.requiredText}>(REQUIRED)</Text></Text>
          <TouchableOpacity 
            style={styles.dropdownSelector}
            onPress={() => { setShowDropdownAbsen(!showDropdownAbsen); setShowDropdownLokasi(false); }}
          >
            <Text style={styles.dropdownSelectorText}>{getDropdownLabel(jenisAbsen)}</Text>
            <Ionicons name={showDropdownAbsen ? "chevron-up" : "chevron-down"} size={18} color="#7f8c8d" />
          </TouchableOpacity>

          {showDropdownAbsen && (
            <View style={styles.dropdownMenu}>
              <TouchableOpacity style={styles.dropdownItem} onPress={() => { setJenisAbsen('absen_datang'); setShowDropdownAbsen(false); }}>
                <Text style={styles.dropdownItemText}>Absen Masuk</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.dropdownItem} onPress={() => { setJenisAbsen('lembur_datang'); setShowDropdownAbsen(false); }}>
                <Text style={styles.dropdownItemText}>Lembur Datang</Text>
              </TouchableOpacity>
            </View>
          )}
        </View>

        {/* DROPDOWN LOKASI */}
        <View style={styles.inputGroup}>
          <Text style={styles.fieldLabelActive}>Lokasi Kerja <Text style={styles.requiredText}>(REQUIRED)</Text></Text>
          <TouchableOpacity 
            style={styles.dropdownSelector}
            onPress={() => { setShowDropdownLokasi(!showDropdownLokasi); setShowDropdownAbsen(false); }}
          >
            <Text style={styles.dropdownSelectorText}>{getLokasiLabel(lokasi)}</Text>
            <Ionicons name={showDropdownLokasi ? "chevron-up" : "chevron-down"} size={18} color="#7f8c8d" />
          </TouchableOpacity>

          {showDropdownLokasi && (
            <View style={styles.dropdownMenu}>
              <TouchableOpacity style={styles.dropdownItem} onPress={() => { setLokasi('kebun_lanud'); setShowDropdownLokasi(false); }}>
                <Text style={styles.dropdownItemText}>Kebun Lanud</Text>
              </TouchableOpacity>
              <TouchableOpacity style={[styles.dropdownItem, { borderBottomWidth: 0 }]} onPress={() => { setLokasi('kebun_sadang'); setShowDropdownLokasi(false); }}>
                <Text style={styles.dropdownItemText}>Kebun Sadang</Text>
              </TouchableOpacity>
            </View>
          )}
        </View>

        <TouchableOpacity style={styles.submitButton} onPress={handleSubmitAttendance} disabled={loading}>
          {loading ? <ActivityIndicator color="#ffffff" /> : (
            <View style={styles.submitButtonInner}>
              <MaterialCommunityIcons name="fingerprint" size={20} color="#ffffff" style={{ marginRight: 8 }} />
              <Text style={styles.submitButtonText}>Submit Attendance</Text>
            </View>
          )}
        </TouchableOpacity>
      </ScrollView>
    </SafeAreaView>
  );
}

// ... styles tetap sama seperti kode Anda ...

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8f9fa',
    paddingTop: Platform.OS === 'android' ? StatusBar.currentHeight : 0,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 14,
    backgroundColor: '#ffffff',
    borderBottomWidth: 1,
    borderColor: '#eaeaea',
  },
  backButton: {
    marginRight: 12,
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#117a65',
  },
  scrollPadding: {
    paddingHorizontal: 20,
    paddingTop: 15,
  },
  greenBanner: {
    backgroundColor: '#4ecb80', 
    borderRadius: 14,
    padding: 20,
    marginBottom: 20,
  },
  bannerTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#ffffff',
    marginBottom: 6,
  },
  bannerSubtitle: {
    fontSize: 12,
    color: '#e8f8f5',
    lineHeight: 18,
  },
  infoCard: {
    backgroundColor: '#ffffff',
    borderRadius: 14,
    padding: 16,
    borderWidth: 1,
    borderColor: '#eaeaea',
    marginBottom: 20,
  },
  inputGroup: {
    marginBottom: 18,
  },
  fieldLabel: {
    fontSize: 12,
    color: '#7f8c8d',
    marginBottom: 6,
    fontWeight: '500',
  },
  fieldLabelActive: {
    fontSize: 13,
    color: '#2c3e50',
    marginBottom: 8,
    fontWeight: '600',
  },
  requiredText: {
    fontSize: 10,
    color: '#27ae60',
    fontWeight: 'bold',
  },
  readOnlyInput: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f1f2f6',
    borderRadius: 10,
    height: 48,
    paddingHorizontal: 14,
  },
  fieldIcon: {
    marginRight: 10,
  },
  readOnlyText: {
    fontSize: 13,
    color: '#57606f',
    fontWeight: '500',
  },
  dropdownSelector: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#dcdde1',
    borderRadius: 12,
    height: 50,
    paddingHorizontal: 16,
  },
  dropdownSelectorText: {
    fontSize: 14,
    color: '#2f3542',
  },
  dropdownMenu: {
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#dcdde1',
    borderRadius: 12,
    marginTop: 6,
    overflow: 'hidden',
    zIndex: 99,
  },
  dropdownItem: {
    paddingVertical: 14,
    paddingHorizontal: 16,
    borderBottomWidth: 1,
    borderColor: '#f1f2f6',
  },
  dropdownItemText: {
    fontSize: 14,
    color: '#2f3542',
  },
  textAreaInput: {
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#dcdde1',
    borderRadius: 12,
    padding: 14,
    fontSize: 14,
    color: '#2f3542',
    height: 120,
  },
  submitButton: {
    backgroundColor: '#0a6847', 
    borderRadius: 25,
    height: 50,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 10,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  submitButtonInner: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  submitButtonText: {
    color: '#ffffff',
    fontSize: 15,
    fontWeight: 'bold',
  },
  noticeText: {
    textAlign: 'center',
    fontSize: 11,
    color: '#7f8c8d',
    lineHeight: 16,
    marginTop: 15,
    paddingHorizontal: 10,
  },
});