import { DarkTheme, DefaultTheme, ThemeProvider } from '@react-navigation/native';
import { Stack } from 'expo-router';
import * as SplashScreen from 'expo-splash-screen';
import { StatusBar } from 'expo-status-bar';
import { useEffect } from 'react';
import 'react-native-reanimated';

import { useColorScheme } from '@/hooks/use-color-scheme';

// Langsung sembunyikan splash agar tidak muncul kotak penampung dari Expo Router
SplashScreen.hideAsync();

export default function RootLayout() {
  const colorScheme = useColorScheme();

return (
    <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
      {/* contentStyle di bawah ini akan memaksa latar belakang putih di seluruh transisi halaman */}
      <Stack screenOptions={{ 
          headerShown: false,
          contentStyle: { backgroundColor: '#ffffff' } 
      }}>
        <Stack.Screen name="login" />
        <Stack.Screen name="owner/index" />
        <Stack.Screen name="karyawan/index" />
        <Stack.Screen name="(tabs)" />
      </Stack>
      <StatusBar style="dark" />
    </ThemeProvider>
  );
}