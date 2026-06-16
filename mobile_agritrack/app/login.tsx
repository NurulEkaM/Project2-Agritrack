/* eslint-disable react/jsx-no-duplicate-props */
import React, { useState } from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  TextInput, 
  TouchableOpacity, 
  Image, 
  Alert, 
  ActivityIndicator,
  Dimensions,
  KeyboardAvoidingView,
  Platform,
  ScrollView
} from 'react-native';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Ionicons } from '@expo/vector-icons';
import { StatusBar } from 'expo-status-bar'; // Menggunakan expo-status-bar agar tidak error render

const { width, height } = Dimensions.get('window');

export default function LoginScreen() {
  const router = useRouter();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const handleLogin = async () => {
    if (!username || !password) {
      Alert.alert('Eror', 'Username dan Password tidak boleh kosong!');
      return;
    }

    setLoading(true);

    try {
      // Sesuaikan IP 10.0.2.2 untuk emulator atau IP asli laptop untuk HP fisik
      const API_URL = 'http://10.0.2.2:8000/api/login'; 
      // const API_URL = 'http://192.168.1.15:8000/api/login'; // Ganti dengan IP asli laptop
      
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ username, password }),
      });

      const data = await response.json();

      if (response.status === 200 && data.success) {
        // Simpan session agar bisa dibaca dashboard
        await AsyncStorage.setItem('user_session', JSON.stringify(data));

        const userRole = data.user.role.toLowerCase();
        
        // Navigasi instan sesuai role
        if (userRole === 'owner') {
          router.replace({
            pathname: '/owner',
            params: { ...data.user }
          });
        } else if (userRole === 'karyawan') {
          router.replace({
            pathname: '/karyawan',
            params: { ...data.user }
          });
        }
      } else {
        Alert.alert('Login Gagal', data.message || 'Kredensial salah');
      }
    } catch (error) {
      console.error(error);
      Alert.alert('Eror', 'Tidak dapat terhubung ke server backend.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView 
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      {/* StatusBar dari expo-status-bar */}
      <StatusBar style="light" />
      
      {/* Header Background */}
      <View style={styles.topSection}>
        <View style={styles.circleDecorator} />
      </View>

      <ScrollView contentContainerStyle={styles.scrollContainer} bounces={false} showsVerticalScrollIndicator={false}>
        <View style={styles.bottomSection}>
          {/* Judul & Subjudul */}
          <View style={styles.textHeader}>
            <Text style={styles.title}>Welcome Back!</Text>
            <Text style={styles.subtitle}>Please login to your account to continue</Text>
          </View>

          {/* Form Input */}
          <View style={styles.form}>
            {/* Input Username */}
            <View style={styles.inputWrapper}>
              <Ionicons name="person-outline" size={20} color="#666" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Username"
                placeholderTextColor="#999"
                value={username}
                onChangeText={setUsername}
                autoCapitalize="none"
              />
            </View>

            {/* Input Password dengan Fitur Mata */}
            <View style={styles.inputWrapper}>
              <Ionicons name="lock-closed-outline" size={20} color="#666" style={styles.inputIcon} />
              <TextInput
                style={styles.input}
                placeholder="Password"
                placeholderTextColor="#999"
                secureTextEntry={!showPassword}
                value={password}
                onChangeText={setPassword}
                autoCapitalize="none"
              />
              <TouchableOpacity 
                onPress={() => setShowPassword(!showPassword)}
                style={styles.eyeIcon}
              >
                <Ionicons 
                  name={showPassword ? "eye-off-outline" : "eye-outline"} 
                  size={22} 
                  color="#666" 
                />
              </TouchableOpacity>
            </View>

            {/* Tombol Login */}
            <TouchableOpacity 
              style={[styles.button, loading && styles.buttonDisabled]} 
              onPress={handleLogin}
              disabled={loading}
            >
              {loading ? (
                <ActivityIndicator color="#117a65" />
              ) : (
                <Text style={styles.buttonText}>LOG IN</Text>
              )}
            </TouchableOpacity>

            <TouchableOpacity style={styles.forgotPass}>
              <Text style={styles.forgotText}>Forgot Password?</Text>
            </TouchableOpacity>
          </View>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#117a65', // Warna Hijau Kiwari Farm
  },
  topSection: {
    height: height * 0.25,
    justifyContent: 'center',
    alignItems: 'center',
  },
  circleDecorator: {
    position: 'absolute',
    width: 200,
    height: 200,
    borderRadius: 100,
    backgroundColor: 'rgba(255,255,255,0.1)',
    top: -50,
    right: -50,
  },
  scrollContainer: {
    flexGrow: 1,
    justifyContent: 'flex-end',
  },
  bottomSection: {
    flex: 1,
    backgroundColor: '#f8f9fa',
    borderTopLeftRadius: 40,
    borderTopRightRadius: 40,
    paddingHorizontal: 30,
    paddingTop: 70,
    minHeight: height * 0.75,
  },
 
  textHeader: {
    alignItems: 'center',
    marginBottom: 35,
  },
  title: {
    fontSize: 28,
    fontWeight: '800',
    color: '#117a65',
    marginBottom: 5,
  },
  subtitle: {
    fontSize: 14,
    color: '#777',
    textAlign: 'center',
  },
  form: {
    width: '100%',
  },
  inputWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#eee',
    borderRadius: 15,
    marginBottom: 15,
    paddingHorizontal: 15,
    height: 60,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    fontSize: 16,
    color: '#333',
  },
  eyeIcon: {
    padding: 5,
  },
  button: {
    width: '100%',
    height: 60,
    backgroundColor: '#fbc02d', // Kuning Emas
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 10,
    elevation: 4,
    shadowColor: '#fbc02d',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
  },
  buttonDisabled: {
    backgroundColor: '#ddd',
  },
  buttonText: {
    fontSize: 18,
    fontWeight: '800',
    color: '#117a65',
    letterSpacing: 1.5,
  },
  forgotPass: {
    marginTop: 20,
    alignItems: 'center',
  },
  forgotText: {
    color: '#117a65',
    fontWeight: '600',
    fontSize: 14,
  },
});