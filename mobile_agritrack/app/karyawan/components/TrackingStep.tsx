import React from 'react';
import { StyleSheet, Text, View } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

interface TrackingStepProps {
  currentStep: number; // Menerima angka 1 sampai 4
}

export default function TrackingStep({ currentStep }: TrackingStepProps) {
  const steps = [
    { id: 1, label: 'Masuk' },
    { id: 2, label: 'Logbook' },
    { id: 3, label: 'Lembur' },
    { id: 4, label: 'Selesai' },
  ];

  return (
    <View style={styles.container}>
      {steps.map((step, index) => {
        const isCompleted = currentStep > step.id;
        const isActive = currentStep === step.id;

        return (
          <React.Fragment key={step.id}>
            {/* Lingkaran Status */}
            <View style={styles.stepWrapper}>
              <View style={[
                styles.circle,
                isCompleted && styles.circleCompleted,
                isActive && styles.circleActive
              ]}>
                {isCompleted ? (
                  <Ionicons name="checkmark" size={14} color="#fff" />
                ) : (
                  <Text style={[styles.stepText, isActive && styles.stepTextActive]}>{step.id}</Text>
                )}
              </View>
              <Text style={[styles.label, isActive && styles.labelActive]}>{step.label}</Text>
            </View>

            {/* Garis Penghubung antar Step */}
            {index < steps.length - 1 && (
              <View style={[
                styles.line,
                currentStep > step.id && styles.lineCompleted
              ]} />
            )}
          </React.Fragment>
        );
      })}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', backgroundColor: '#fff', padding: 16, borderRadius: 16, borderWidth: 1, borderColor: '#f0f0f0', marginBottom: 20 },
  stepWrapper: { alignItems: 'center', width: 60 },
  circle: { width: 28, height: 28, borderRadius: 14, backgroundColor: '#eaeded', justifyContent: 'center', alignItems: 'center' },
  circleActive: { backgroundColor: '#117a65' },
  circleCompleted: { backgroundColor: '#2ecc71' },
  stepText: { fontSize: 12, color: '#7f8c8d', fontWeight: 'bold' },
  stepTextActive: { color: '#fff' },
  label: { fontSize: 10, color: '#95a5a6', marginTop: 4, textAlign: 'center', fontWeight: '500' },
  labelActive: { color: '#117a65', fontWeight: 'bold' },
  line: { flex: 1, height: 2, backgroundColor: '#eaeded', position: 'relative', top: -8, marginHorizontal: -4 },
  lineCompleted: { backgroundColor: '#2ecc71' },
});