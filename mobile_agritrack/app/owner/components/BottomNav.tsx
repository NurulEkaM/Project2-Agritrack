import React from 'react';
import { 
  StyleSheet, 
  Text, 
  View, 
  TouchableOpacity, 
  Platform 
} from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

interface BottomNavProps {
  activeScreen: 'Home' | 'Pengeluaran' | 'Karyawan' | 'Laporan' | 'Profile';
  onNavPress?: (screen: 'Home' | 'Pengeluaran' | 'Karyawan' | 'Laporan' | 'Profile') => void;
}

export default function BottomNav({ activeScreen, onNavPress }: BottomNavProps) {
  return (
    <View style={styles.bottomNav}>
      {/* Tombol Home */}
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Home')}
      >
        {activeScreen === 'Home' ? (
          <View style={styles.activeNavBg}>
            <MaterialCommunityIcons name="view-grid" size={20} color="#ffffff" />
          </View>
        ) : (
          <MaterialCommunityIcons name="view-grid-outline" size={22} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Home' && styles.activeNavText]}>
          Home
        </Text>
      </TouchableOpacity>

      {/* Tombol Pengeluaran */}
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Pengeluaran')}
      >
        {activeScreen === 'Pengeluaran' ? (
          <View style={styles.activeNavBg}>
            <MaterialCommunityIcons name="fingerprint" size={22} color="#ffffff" />
          </View>
        ) : (
          <MaterialCommunityIcons name="fingerprint" size={24} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Pengeluaran' && styles.activeNavText]}>
          Pengeluaran
        </Text>
      </TouchableOpacity>

      {/* Tombol Karyawan */}
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Karyawan')}
      >
        {activeScreen === 'Karyawan' ? (
          <View style={styles.activeNavBg}>
            <Ionicons name="calendar" size={20} color="#ffffff" />
          </View>
        ) : (
          <Ionicons name="calendar-outline" size={22} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Karyawan' && styles.activeNavText]}>
          Karyawan
        </Text>
      </TouchableOpacity>

      {/* Tombol Laporan */}
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Laporan')}
      >
        {activeScreen === 'Laporan' ? (
          <View style={styles.activeNavBg}>
            <MaterialCommunityIcons name="file-document" size={20} color="#ffffff" />
          </View>
        ) : (
          <MaterialCommunityIcons name="file-document-outline" size={22} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Laporan' && styles.activeNavText]}>
          Laporan
        </Text>
      </TouchableOpacity>
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Profile')}
      >
        {activeScreen === 'Profile' ? (
          <View style={styles.activeNavBg}>
            <Ionicons name="person" size={20} color="#ffffff" />
          </View>
        ) : (
          <Ionicons name="person-outline" size={22} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Profile' && styles.activeNavText]}>
          Profile
        </Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  bottomNav: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    height: 70,
    backgroundColor: '#ffffff',
    borderTopLeftRadius: 25,
    borderTopRightRadius: 25,
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    paddingBottom: Platform.OS === 'ios' ? 12 : 0,
    elevation: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: -3 },
    shadowOpacity: 0.06,
    shadowRadius: 10,
  },
  navItem: {
    alignItems: 'center',
    justifyContent: 'center',
    minWidth: 60,
  },
  activeNavBg: {
    backgroundColor: '#117a65',
    width: 40,
    height: 32,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 2,
  },
  navText: {
    fontSize: 10,
    color: '#bdc3c7',
    marginTop: 2,
  },
  activeNavText: {
    color: '#117a65',
    fontWeight: 'bold',
  },
});