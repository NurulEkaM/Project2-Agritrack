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
  ActivityIndicator,
  Image // Tambahkan Image
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { router } from 'expo-router';
import * as ImagePicker from 'expo-image-picker'; // 1. Import ImagePicker

export default function InputProdukScreen() {
  const [loading, setLoading] = useState(false);
  const [image, setImage] = useState<any>(null); // State untuk simpan info gambar
  const [form, setForm] = useState({
    nama_produk: '',
    harga_satuan: '',
    stok: '',
    deskripsi: '',
  });

  // 2. Fungsi Memilih Gambar
  const pickImage = async () => {
    let result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [4, 3],
      quality: 0.7, // Kompres sedikit agar upload lebih cepat
    });

    if (!result.canceled) {
      setImage(result.assets[0]);
    }
  };

  const handleSubmit = async () => {
    if (!form.nama_produk || !form.harga_satuan || !form.stok) {
      Alert.alert('Error', 'Mohon isi field utama (Nama, Harga, dan Stok)');
      return;
    }

    setLoading(true);

    // 3. Gunakan FormData (Wajib untuk upload file)
    const formData = new FormData();
    formData.append('nama_produk', form.nama_produk);
    formData.append('harga_satuan', form.harga_satuan);
    formData.append('stok', form.stok);
    formData.append('deskripsi', form.deskripsi);

    if (image) {
      formData.append('gambar', { // <--- PASTIKAN namanya 'gambar' bukan 'image' atau 'photo'
        uri: image.uri,
        name: 'product_image.jpg',
        type: 'image/jpeg',
      } as any);
    }

    try {
      const response = await fetch('http://10.0.2.2:8000/api/add-produk', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          // Note: Jangan set Content-Type secara manual saat kirim FormData
        },
        body: formData,
      });

      const result = await response.json();

      if (response.ok) {
        Alert.alert('Sukses', 'Produk berhasil ditambahkan');
        router.back();
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
      
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={24} color="#2c3e50" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>NEW PRODUCT</Text>
        <View style={styles.profileCircle} />
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContent}>
        
        <Text style={styles.label}>Product Image</Text>
        {/* 4. Tampilan Box Gambar yang bisa diklik */}
        <TouchableOpacity style={styles.imageBox} onPress={pickImage}>
          {image ? (
            <Image source={{ uri: image.uri }} style={styles.selectedImage} />
          ) : (
            <>
              <Ionicons name="camera-outline" size={40} color="#bdc3c7" />
              <Text style={styles.imageText}>Tap to upload high-res imagery</Text>
            </>
          )}
        </TouchableOpacity>

        {/* Sisanya tetap sama */}
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
            style={[styles.input, { height: 80, textAlignVertical: 'top', paddingTop: 10 }]}
            placeholder="e.g. kg, liters, bags"
            multiline
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
  // ... style Anda sebelumnya ...
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
    overflow: 'hidden' // Agar gambar tidak keluar dari border radius
  },
  selectedImage: {
    width: '100%',
    height: '100%',
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