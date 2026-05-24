import React, { useState } from 'react';
import { StyleSheet, Text, View, SafeAreaView, TouchableOpacity, ScrollView, Alert, ActivityIndicator } from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useRouter, useLocalSearchParams } from 'expo-router';

const DetailKreditScreen = () => {
  const router = useRouter();
  const params = useLocalSearchParams();
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Helper untuk format Rupiah
  const formatRupiah = (number: string | string[] | undefined) => {
    const val = typeof number === 'string' ? parseInt(number) : 0;
    return new Intl.NumberFormat('id-ID').format(val);
  };

  // Fungsi untuk mengupdate status ke API Laravel
  const handleUpdateStatus = async (newStatus: 'setuju' | 'tidak disetuju') => {
    Alert.alert(
      "Konfirmasi",
      `Apakah Anda yakin ingin mengubah status menjadi ${newStatus}?`,
      [
        { text: "Batal", style: "cancel" },
        {
          text: "Ya, Lanjutkan",
          onPress: async () => {
            setIsSubmitting(true);
            try {
              // Ganti IP 10.0.2.2 sesuai dengan setup server Anda
              const response = await fetch(`http://10.0.2.2:8000/api/kredit-update/${params.id}`, {
                method: 'PUT',
                headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                },
                body: JSON.stringify({
                  status: newStatus,
                }),
              });

              const result = await response.json();

              if (response.ok) {
                Alert.alert("Berhasil", `Status pengeluaran telah diubah menjadi ${newStatus}.`);
                router.replace('/owner/pengeluaran'); // Kembali ke list dan refresh
              } else {
                Alert.alert("Gagal", result.message || "Terjadi kesalahan saat update.");
              }
            } catch (error) {
              console.error(error);
              Alert.alert("Error", "Tidak dapat terhubung ke server.");
            } finally {
              setIsSubmitting(false);
            }
          }
        }
      ]
    );
  };

  return (
    <SafeAreaView style={styles.container}>
      {/* Header Nav */}
      <View style={styles.headerNav}>
        <TouchableOpacity onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={24} color="#117a65" />
        </TouchableOpacity>
        <Text style={styles.headerNavTitle}>Detail Kredit</Text>
      </View>

      <ScrollView contentContainerStyle={styles.content}>
        {/* Icon & Title Section */}
        <View style={styles.topSection}>
          <View style={styles.iconCircle}>
            <MaterialCommunityIcons name="cash-multiple" size={32} color="#d4ac0d" />
          </View>
          <Text style={styles.mainTitle}>{params.nama}</Text>
          <Text style={styles.dateText}>• {params.tanggal}</Text>
          <Text style={styles.mainAmount}>Rp {formatRupiah(params.saldo)}</Text>
        </View>

        {/* Description Card */}
        <View style={styles.descCard}>
          <Text style={styles.descLabel}>DESCRIPTION</Text>
          <Text style={styles.descValue}>{params.keterangan || "Tidak ada deskripsi."}</Text>
          
          <View style={styles.statusInfo}>
            <Text style={styles.descLabel}>STATUS SAAT INI</Text>
            <View style={styles.badgeTunggu}>
              <Text style={styles.badgeText}>{params.status?.toString().toUpperCase()}</Text>
            </View>
          </View>
        </View>

        {/* Action Buttons */}
        <View style={styles.buttonContainer}>
          {isSubmitting ? (
            <ActivityIndicator size="large" color="#117a65" />
          ) : (
            <>
              <TouchableOpacity 
                style={styles.btnConfirm} 
                onPress={() => handleUpdateStatus('setuju')}
              >
                <Ionicons name="checkmark-circle" size={20} color="#fff" style={{marginRight: 8}} />
                <Text style={styles.btnConfirmText}>CONFIRM (setuju)</Text>
              </TouchableOpacity>

              <TouchableOpacity 
                style={styles.btnReject} 
                onPress={() => handleUpdateStatus('tidak disetuju')}
              >
                <Ionicons name="close-circle-outline" size={20} color="#c0392b" style={{marginRight: 8}} />
                <Text style={styles.btnRejectText}>REJECT (tidak setuju)</Text>
              </TouchableOpacity>
            </>
          )}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8faf9' },
  headerNav: { flexDirection: 'row', alignItems: 'center', padding: 20, gap: 15 },
  headerNavTitle: { fontSize: 18, fontWeight: 'bold', color: '#117a65' },
  content: { padding: 20, alignItems: 'center' },
  topSection: { alignItems: 'center', marginBottom: 30 },
  iconCircle: { width: 70, height: 70, borderRadius: 35, backgroundColor: '#f9d976', justifyContent: 'center', alignItems: 'center', marginBottom: 15 },
  mainTitle: { fontSize: 20, fontWeight: 'bold', color: '#333' },
  dateText: { fontSize: 12, color: '#7f8c8d', marginVertical: 5 },
  mainAmount: { fontSize: 28, fontWeight: 'bold', color: '#117a65', marginTop: 5 },
  descCard: { backgroundColor: '#fff', width: '100%', padding: 20, borderRadius: 15, elevation: 2, shadowColor: '#000', shadowOpacity: 0.05, shadowRadius: 10 },
  descLabel: { fontSize: 11, fontWeight: 'bold', color: '#95a5a6', marginBottom: 10 },
  descValue: { fontSize: 14, color: '#34495e', lineHeight: 20, marginBottom: 20 },
  statusInfo: { borderTopWidth: 1, borderTopColor: '#f1f1f1', paddingTop: 15 },
  badgeTunggu: { backgroundColor: '#fef9e7', alignSelf: 'flex-start', paddingHorizontal: 12, paddingVertical: 4, borderRadius: 8 },
  badgeText: { color: '#d4ac0d', fontWeight: 'bold', fontSize: 12 },
  buttonContainer: { width: '100%', marginTop: 40, gap: 15 },
  btnConfirm: { backgroundColor: '#097951', flexDirection: 'row', height: 55, borderRadius: 30, justifyContent: 'center', alignItems: 'center', elevation: 3 },
  btnConfirmText: { color: '#fff', fontWeight: 'bold', fontSize: 15 },
  btnReject: { backgroundColor: 'transparent', flexDirection: 'row', height: 55, borderRadius: 30, justifyContent: 'center', alignItems: 'center', borderWidth: 1.5, borderColor: '#c0392b' },
  btnRejectText: { color: '#c0392b', fontWeight: 'bold', fontSize: 15 },
});

export default DetailKreditScreen;