import React, { useState } from 'react';
import {
  StyleSheet,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  ScrollView,
  SafeAreaView,
  StatusBar,
  Alert,
  ActivityIndicator
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { router } from 'expo-router';

export default function InputProdukScreen() {
  const [loading, setLoading] = useState(false);
  const [form, setForm] = useState({
    nama_produk: '',
    harga_satuan: '',
    stok: '',
    deskripsi: '', // Deskripsi dipetakan ke field "Unit" atau "Category" di UI jika perlu
  });

  const handleSubmit = async () => {
    if (!form.nama_produk || !form.harga_satuan || !form.stok) {
      Alert.alert('Error', 'Mohon isi field utama (Nama, Harga, dan Stok)');
      return;
    }

    setLoading(true);
    try {
      const response = await fetch('http://10.0.2.2:8000/api/add-produk', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          nama_produk: form.nama_produk,
          harga_satuan: parseFloat(form.harga_satuan),
          stok: parseInt(form.stok),
          deskripsi: form.deskripsi,
        }),
      });

      const result = await response.json();

      if (response.ok) {
        Alert.alert('Sukses', 'Produk berhasil ditambahkan');
        router.back(); // Kembali ke halaman list produk
      } else {
        Alert.alert('Gagal', result.message || 'Terjadi kesalahan');
      }
    } catch (error) {
      console.error(error);
      Alert.alert('Error', 'Tidak dapat terhubung ke server');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      
      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={24} color="#2c3e50" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>NEW PRODUCT</Text>
        <View style={styles.profileCircle} />
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>
        
        {/* Product Image Placeholder (Dikosongkan sesuai permintaan) */}
        <Text style={styles.label}>Product Image</Text>
        <View style={styles.imageBox}>
          <Ionicons name="camera-outline" size={40} color="#bdc3c7" />
          <Text style={styles.imageText}>Tap to upload high-res imagery</Text>
        </View>

        {/* Form Inputs */}
        <View style={styles.formGroup}>
          <Text style={styles.label}>Product Name</Text>
          <TextInput
            style={styles.input}
            placeholder="e.g. Premium Arabica Seeds"
            value={form.nama_produk}
            onChangeText={(val) => setForm({ ...form, nama_produk: val })}
          />
        </View>

        <View style={styles.formGroup}>
          <Text style={styles.label}>Description / Unit</Text>
          <TextInput
            style={styles.inputDeskripsi}
            placeholder="e.g. kg, liters, bags"
            value={form.deskripsi}
            onChangeText={(val) => setForm({ ...form, deskripsi: val })}
          />
        </View>

        <View style={styles.formGroup}>
          <Text style={styles.label}>Current Stock</Text>
          <TextInput
            style={styles.input}
            placeholder="0"
            keyboardType="numeric"
            value={form.stok}
            onChangeText={(val) => setForm({ ...form, stok: val })}
          />
        </View>

        <View style={styles.formGroup}>
          <Text style={styles.label}>Unit Price (Rp)</Text>
          <TextInput
            style={styles.input}
            placeholder="Rp 0.00"
            keyboardType="numeric"
            value={form.harga_satuan}
            onChangeText={(val) => setForm({ ...form, harga_satuan: val })}
          />
        </View>

        {/* Submit Button */}
        <TouchableOpacity 
          style={[styles.submitButton, loading && { backgroundColor: '#95a5a6' }]} 
          onPress={handleSubmit}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.submitText}>SUBMIT <Ionicons name="play-forward" size={16} color="#fff" /></Text>
          )}
        </TouchableOpacity>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fcfcfc' },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  inputDeskripsi: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#eee',
    borderRadius: 10,
    paddingHorizontal: 15,
    height: 100,
    fontSize: 14,
    color: '#2c3e50',
    textAlignVertical: 'top',
    },
  headerTitle: { fontSize: 18, fontWeight: 'bold', color: '#2c3e50' },
  profileCircle: { width: 35, height: 35, borderRadius: 17.5, backgroundColor: '#ddd' },
  scrollContent: { padding: 20 },
  label: { fontSize: 14, color: '#7f8c8d', marginBottom: 8, fontWeight: '500' },
  imageBox: {
    height: 180,
    backgroundColor: '#f8f9fa',
    borderRadius: 15,
    borderWidth: 1,
    borderColor: '#eee',
    borderStyle: 'dashed',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 20,
  },
  imageText: { color: '#bdc3c7', fontSize: 12, marginTop: 10 },
  formGroup: { marginBottom: 20 },
  input: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#eee',
    borderRadius: 10,
    paddingHorizontal: 15,
    height: 50,
    fontSize: 14,
    color: '#2c3e50',
  },
  submitButton: {
    backgroundColor: '#4ecb80',
    height: 55,
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 10,
    marginBottom: 30,
    elevation: 3,
  },
  submitText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
});