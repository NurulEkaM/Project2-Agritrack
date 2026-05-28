import React, { useState } from 'react';
import {
  StyleSheet,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  ScrollView,
  SafeAreaView,
  Alert,
  ActivityIndicator,
  Image // Tambahkan Image
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { router, useLocalSearchParams } from 'expo-router';
import * as ImagePicker from 'expo-image-picker'; // Tambahkan ImagePicker

export default function EditProdukScreen() {
  const params = useLocalSearchParams();
  const [loading, setLoading] = useState(false);
  const [newImage, setNewImage] = useState<any>(null); // Untuk menampung gambar baru jika diganti
  
  const [form, setForm] = useState({
    nama_produk: params.nama_produk as string || '',
    harga_satuan: params.harga_satuan as string || '',
    stok: params.stok?.toString() || '',
    deskripsi: params.deskripsi as string || '',
  });

  const pickImage = async () => {
    let result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [4, 3],
      quality: 0.7,
    });

    if (!result.canceled) {
      setNewImage(result.assets[0]);
    }
  };

  const handleUpdate = async () => {
    setLoading(true);
    
    // Gunakan FormData karena kita mungkin mengirim file gambar
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Laravel membutuhkan ini jika method spoofing PUT via FormData
    formData.append('nama_produk', form.nama_produk);
    formData.append('harga_satuan', form.harga_satuan);
    formData.append('stok', form.stok);
    formData.append('deskripsi', form.deskripsi);

    if (newImage) {
      const uri = newImage.uri;
      const fileType = uri.split('.').pop();
      formData.append('gambar', {
        uri: uri,
        name: `updated_produk.${fileType}`,
        type: `image/${fileType}`,
      } as any);
    }

    try {
      // Gunakan POST dengan _method PUT untuk upload file di Laravel
      const response = await fetch(`http://10.0.2.2:8000/api/update-produk/${params.id_produk}`, {
        method: 'POST', 
        headers: {
          'Accept': 'application/json',
        },
        body: formData,
      });

      if (response.ok) {
        Alert.alert('Sukses', 'Produk berhasil diperbarui');
        router.replace('/karyawan/Produk');
      } else {
        const err = await response.json();
        Alert.alert('Gagal', err.message || 'Gagal update data');
      }
    } catch (error) {
      Alert.alert('Error', 'Gagal terhubung ke server');
    } finally {
      setLoading(false);
    }
  };

  // ... handleDelete tetap sama ...
  const handleDelete = () => {
    Alert.alert(
      'Hapus Produk',
      'Apakah Anda yakin ingin menghapus produk ini?',
      [
        { text: 'Batal', style: 'cancel' },
        { 
          text: 'Hapus', 
          style: 'destructive',
          onPress: async () => {
            try {
              const response = await fetch(`http://10.0.2.2:8000/api/delete-produk/${params.id_produk}`, {
                method: 'DELETE'
              });
              if (response.ok) {
                router.replace('/karyawan/Produk');
              }
            } catch (error) {
              Alert.alert('Error', 'Gagal menghapus produk');
            }
          }
        }
      ]
    );
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={24} color="black" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>EDIT PRODUCT</Text>
        <View style={{ width: 24 }} />
      </View>

      <ScrollView contentContainerStyle={styles.scrollContent}>
        <View style={styles.imageContainer}>
          <TouchableOpacity style={styles.imageBox} onPress={pickImage}>
            {/* Logika Tampilan Gambar: Prioritas gambar baru, lalu gambar lama, lalu icon */}
            {newImage ? (
              <Image source={{ uri: newImage.uri }} style={styles.imagePreview} />
            ) : params.gambar ? (
              <Image 
                source={{ uri: `http://10.0.2.2:8000/storage/${params.gambar}` }} 
                style={styles.imagePreview} 
              />
            ) : (
              <Ionicons name="leaf" size={60} color="#117a65" />
            )}
            
            <View style={styles.editImageIcon}>
              <Ionicons name="camera" size={16} color="black" />
            </View>
          </TouchableOpacity>
          <Text style={styles.refText}>PRODUCT ID: #{params.id_produk}</Text>
        </View>

        {/* Input Fields */}
        <Text style={styles.label}>Product Name</Text>
        <TextInput 
          style={styles.input} 
          value={form.nama_produk} 
          onChangeText={(t) => setForm({...form, nama_produk: t})}
        />

        <View style={styles.row}>
          <View style={{ flex: 1, marginRight: 10 }}>
            <Text style={styles.label}>Current Stock</Text>
            <TextInput 
              style={styles.input} 
              keyboardType="numeric" 
              value={form.stok}
              onChangeText={(t) => setForm({...form, stok: t})}
            />
          </View>
          <View style={{ flex: 1 }}>
            <Text style={styles.label}>Price (IDR)</Text>
            <TextInput 
              style={styles.input} 
              keyboardType="numeric" 
              value={form.harga_satuan}
              onChangeText={(t) => setForm({...form, harga_satuan: t})}
            />
          </View>
        </View>

        <Text style={styles.label}>Description</Text>
        <TextInput 
          style={[styles.input, styles.textArea]} 
          multiline 
          numberOfLines={4}
          value={form.deskripsi}
          onChangeText={(t) => setForm({...form, deskripsi: t})}
        />

        <TouchableOpacity style={styles.updateButton} onPress={handleUpdate}>
          {loading ? <ActivityIndicator color="white" /> : <Text style={styles.updateText}>SAVE CHANGES</Text>}
        </TouchableOpacity>

        <Text style={[styles.label, { color: '#e74c3c', marginTop: 30 }]}>Danger Zone</Text>
        <TouchableOpacity style={styles.deleteButton} onPress={handleDelete}>
          <Ionicons name="trash-outline" size={18} color="#e74c3c" />
          <Text style={styles.deleteText}> DELETE PRODUCT</Text>
        </TouchableOpacity>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },
  header: { flexDirection: 'row', justifyContent: 'space-between', padding: 20, alignItems: 'center' },
  headerTitle: { fontSize: 16, fontWeight: 'bold' },
  scrollContent: { padding: 20 },
  imageContainer: { alignItems: 'center', marginBottom: 30 },
  imageBox: { width: 120, height: 120, backgroundColor: '#f0f0f0', borderRadius: 20, justifyContent: 'center', alignItems: 'center', overflow: 'hidden' },
  imagePreview: { width: '100%', height: '100%' },
  editImageIcon: { position: 'absolute', bottom: 5, right: 5, backgroundColor: '#ffd700', padding: 8, borderRadius: 10, elevation: 5 },
  refText: { marginTop: 10, fontSize: 12, color: '#bdc3c7' },
  label: { fontSize: 12, fontWeight: 'bold', color: '#7f8c8d', marginBottom: 8 },
  input: { borderWidth: 1, borderColor: '#f0f0f0', borderRadius: 10, padding: 12, marginBottom: 20, color: '#2c3e50' },
  row: { flexDirection: 'row', justifyContent: 'space-between' },
  textArea: { height: 100, textAlignVertical: 'top' },
  updateButton: { backgroundColor: '#117a65', padding: 15, borderRadius: 12, alignItems: 'center' },
  updateText: { color: 'white', fontWeight: 'bold' },
  deleteButton: { flexDirection: 'row', borderWidth: 1, borderColor: '#f0f0f0', padding: 15, borderRadius: 12, alignItems: 'center', justifyContent: 'center', marginTop: 10 },
  deleteText: { color: '#e74c3c', fontWeight: 'bold', fontSize: 13 }
});