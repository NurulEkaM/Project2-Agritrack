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
  activeScreen: 'Home' | 'Absensi' | 'Gaji' | 'Profile';
  onNavPress?: (screen: string) => void;
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

      {/* Tombol Absensi */}
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Absensi')}
      >
        {activeScreen === 'Absensi' ? (
          <View style={styles.activeNavBg}>
            <MaterialCommunityIcons name="fingerprint" size={22} color="#ffffff" />
          </View>
        ) : (
          <MaterialCommunityIcons name="fingerprint" size={24} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Absensi' && styles.activeNavText]}>
          Absensi
        </Text>
      </TouchableOpacity>

      {/* Tombol Gaji */}
      <TouchableOpacity 
        style={styles.navItem} 
        onPress={() => onNavPress?.('Gaji')}
      >
        {activeScreen === 'Gaji' ? (
          <View style={styles.activeNavBg}>
            <Ionicons name="calendar" size={20} color="#ffffff" />
          </View>
        ) : (
          <Ionicons name="calendar-outline" size={22} color="#bdc3c7" />
        )}
        <Text style={[styles.navText, activeScreen === 'Gaji' && styles.activeNavText]}>
          Gaji
        </Text>
      </TouchableOpacity>

      {/* Tombol Profile */}
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