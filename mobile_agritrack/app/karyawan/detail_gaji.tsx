import React, { useState, useEffect } from 'react';
import {
  StyleSheet,
  Text,
  View,
  TouchableOpacity,
  SafeAreaView,
  StatusBar,
  ActivityIndicator,
  ScrollView
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useRouter, useLocalSearchParams } from 'expo-router';

// Interface sesuai dengan database Laravel Anda
interface DetailGajiData {
  id_gaji: number;
  periode_mulai: string;
  periode_selesai: string;
  tanggal_dibayar: string;
  total_gaji_pokok: number; // Gaji Pokok Harian (hasil gaji bersih dasar)
  jam_lembur: number;
  tarif_lembur: number;
  total_lembur: number;
  gaji_bersih_akhir: number;
  keterangan: string;
}

export default function DetailGajiScreen() {
  const router = useRouter();
  const { id_gaji } = useLocalSearchParams(); 
  const [loading, setLoading] = useState<boolean>(true);
  const [gajiDb, setGajiDb] = useState<DetailGajiData | null>(null);

  useEffect(() => {
    if (id_gaji) {
      fetchDetailGaji();
    }
  }, [id_gaji]);

  const fetchDetailGaji = async () => {
    setLoading(true);
    try {
      const API_URL = `http://10.0.2.2:8000/api/gaji/detail/${id_gaji}`;
      const response = await fetch(API_URL);
      const data = await response.json();

      if (data.success && data.result) {
        setGajiDb(data.result);
      }
    } catch (error) {
      console.error("Gagal memuat detail gaji:", error);
    } finally {
      setLoading(false);
    }
  };

  const formatRupiah = (angka: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(angka);
  };

  // --- PEMROSESAN TANGGAL ---
  const dapatkanInformasiTanggal = (dateString?: string) => {
    if (!dateString) return { periodeMulai: '-', periodeSelesai: '-', tanggalBayar: '-' };
    const tglUtama = new Date(dateString);
    
    // Periode mingguan (H-6 dari tanggal gajian)
    const tglMulai = new Date(tglUtama);
    tglMulai.setDate(tglUtama.getDate() - 6);

    const opsiMulai: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'short' };
    const opsiSelesai: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'short', year: 'numeric' };
    const opsiBayar: Intl.DateTimeFormatOptions = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };

    return {
      periodeMulai: tglMulai.toLocaleDateString('id-ID', opsiMulai),
      periodeSelesai: tglUtama.toLocaleDateString('id-ID', opsiSelesai),
      tanggalBayar: tglUtama.toLocaleDateString('id-ID', opsiBayar)
    };
  };

  const infoTanggal = dapatkanInformasiTanggal(gajiDb?.tanggal_dibayar);

  // Konstanta tarif lembur
  const TARIF_LEMBUR_PER_JAM = 70000;
  const jamLemburDb = gajiDb?.total_lembur || 0;
  const nominalLembur = jamLemburDb * TARIF_LEMBUR_PER_JAM;

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#117a65" />
        <Text style={styles.loadingText}>Memuat Rincian Gaji...</Text>
      </View>
    );
  }

  // ... kode atas tetap sama ...

return (
  <SafeAreaView style={styles.container}>
    <StatusBar barStyle="dark-content" backgroundColor="#ffffff" />

    {/* --- TOP BAR --- */}
    <View style={styles.topBar}>
      <TouchableOpacity style={styles.backButton} onPress={() => router.push('/karyawan/gaji')}>
        <Ionicons name="chevron-back" size={24} color="#117a65" />
        <Text style={styles.backButtonText}>Data Gaji</Text>
      </TouchableOpacity>
    </View>

    <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollPadding}>
      
      {/* --- JUDUL HALAMAN --- */}
      <View style={styles.titleSection}>
        <Text style={styles.mainTitle}>Rincian Gaji Mingguan</Text>
        <Text style={styles.periodText}>
          Periode: {infoTanggal.periodeMulai} - {infoTanggal.periodeSelesai}
        </Text>
      </View>

      {/* --- BANNER HIJAU TOTAL AKHIR (GAJI BERSIH + LEMBUR) --- */}
      <View style={styles.greenCard}>
        <View style={styles.greenCardLeft}>
          <Text style={styles.cardLabel}>Total Gaji Minggu Ini</Text>
          <Text style={styles.cardAmount}>
            {formatRupiah(gajiDb?.gaji_bersih_akhir || 0)}
          </Text>
          <View style={styles.payDateRow}>
            <Ionicons name="calendar-outline" size={14} color="#ffffff" style={{ marginRight: 6 }} />
            <Text style={styles.payDateText}>Dibayar: {infoTanggal.tanggalBayar}</Text>
          </View>
        </View>
        
        <View style={styles.iconContainer}>
          <MaterialCommunityIcons name="flower-outline" size={70} color="rgba(255, 255, 255, 0.15)" />
        </View>
      </View>

      {/* --- BUTTON DOWNLOAD SLIP --- */}
      <View style={styles.downloadRow}>
        <TouchableOpacity style={styles.downloadButton} activeOpacity={0.7}>
          <Ionicons name="download-outline" size={20} color="#117a65" />
        </TouchableOpacity>
      </View>

      {/* --- RINCIAN PERHITUNGAN SECTION --- */}
      <View style={styles.sectionDividerRow}>
        <MaterialCommunityIcons name="file-document-edit-outline" size={18} color="#117a65" />
        <Text style={styles.sectionDividerTitle}>Rincian Perhitungan</Text>
      </View>

      {/* --- BOX RINCIAN DATA MURNI DATABASE --- */}
      <View style={styles.detailsBox}>
        
        {/* Bagian 1: Hari Kerja / Gaji Pokok Mingguan (Hasil User Gaji * 5) */}
        <View style={styles.detailItemRow}>
          <View>
            <Text style={styles.itemTitle}>Gaji Pokok Harian</Text>
            <Text style={styles.itemTitle}>Rp.{gajiDb?.total_gaji_pokok}</Text>
            <Text style={styles.itemSubtitle}>Gaji Bersih Harian </Text>
          </View>
          <Text style={styles.itemValue}>
            {formatRupiah(gajiDb?.total_gaji_pokok || 0)}
          </Text>
        </View>

        <View style={styles.lineSeparator} />

        {/* Bagian 2: Lembur (Jam Lembur dari DB * 10000) */}
        <View style={styles.detailItemRow}>
          <View>
            <Text style={styles.itemTitle}>Lembur (Overtime)</Text>
            <Text style={styles.itemSubtitle}>
              {gajiDb?.jam_lembur || 0} Jam $\times$ {formatRupiah(gajiDb?.tarif_lembur || 10000)}
            </Text>
          </View>
          <Text style={[styles.itemValue, { color: '#27ae60' }]}>
            + {formatRupiah(gajiDb?.total_lembur || 0)}
          </Text>
        </View>

        <View style={styles.lineSeparator} />

        {/* Bagian 3: Keterangan Minggu Slip */}
        <View style={styles.detailItemRow}>
          <View>
            <Text style={[styles.itemTitle, { color: '#000000' }]}>Keterangan Slip</Text>
            <Text style={[styles.itemSubtitle, { color: '#7f8c8d' }]}>Status Pembayaran</Text>
          </View>
          <Text style={[styles.itemValue, { color: '#117a65' }]}>
            {gajiDb?.keterangan || 'Normal'}
          </Text>
        </View>

      </View>
    </ScrollView>
  </SafeAreaView>
);
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8f9fa' },
  loadingContainer: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#f8f9fa' },
  loadingText: { marginTop: 10, fontSize: 13, color: '#7f8c8d' },
  topBar: { height: 50, backgroundColor: '#ffffff', justifyContent: 'center', paddingHorizontal: 10 },
  backButton: { flexDirection: 'row', alignItems: 'center' },
  backButtonText: { fontSize: 15, fontWeight: '600', color: '#117a65', marginLeft: 2 },
  scrollPadding: { paddingHorizontal: 20, paddingTop: 15, paddingBottom: 40 },
  titleSection: { marginBottom: 20 },
  mainTitle: { fontSize: 22, fontWeight: 'bold', color: '#2c3e50' },
  periodText: { fontSize: 12, color: '#7f8c8d', marginTop: 4, fontWeight: '500' },
  greenCard: {
    backgroundColor: '#52be80',
    borderRadius: 16,
    padding: 20,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    position: 'relative',
    overflow: 'hidden',
  },
  greenCardLeft: { flex: 1, zIndex: 2 },
  cardLabel: { fontSize: 12, color: 'rgba(255, 255, 255, 0.85)', fontWeight: '500' },
  cardAmount: { fontSize: 26, fontWeight: 'bold', color: '#ffffff', marginVertical: 8 },
  payDateRow: { flexDirection: 'row', alignItems: 'center', marginTop: 4 },
  payDateText: { fontSize: 11, color: '#ffffff', fontWeight: '500' },
  iconContainer: { position: 'absolute', right: -10, bottom: -5, opacity: 0.8 },
  downloadRow: { alignItems: 'flex-end', marginTop: 15, marginBottom: 10 },
  downloadButton: { backgroundColor: '#d5f5e3', width: 42, height: 42, borderRadius: 21, justifyContent: 'center', alignItems: 'center' },
  sectionDividerRow: { flexDirection: 'row', alignItems: 'center', marginBottom: 15 },
  sectionDividerTitle: { fontSize: 13, fontWeight: 'bold', color: '#2c3e50', marginLeft: 6 },
  detailsBox: {
    backgroundColor: '#ffffff',
    borderRadius: 14,
    paddingVertical: 4,
    paddingHorizontal: 16,
    borderWidth: 1,
    borderColor: '#e2e8f0',
    elevation: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.03,
    shadowRadius: 2,
  },
  detailItemRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingVertical: 14 },
  itemTitle: { fontSize: 13, fontWeight: '600', color: '#2c3e50' },
  itemSubtitle: { fontSize: 11, color: '#95a5a6', marginTop: 3, fontWeight: '500' },
  itemValue: { fontSize: 13, fontWeight: 'bold', color: '#2c3e50' },
  lineSeparator: { height: 1, backgroundColor: '#f1f2f6' },
});