import { StyleSheet, Dimensions } from 'react-native';

const { width } = Dimensions.get('window');

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#ffffff',
  },
  header: {
    paddingHorizontal: 20,
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderColor: '#f2f4f4',
  },
  headerTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#117a65',
  },
  scrollPadding: {
    paddingHorizontal: 20,
    paddingTop: 15,
  },
  introSection: {
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 15,
    fontWeight: '700',
    color: '#2c3e50',
    marginBottom: 4,
  },
  sectionSubtitle: {
    fontSize: 12,
    color: '#7f8c8d',
  },
  mainStatsBox: {
    backgroundColor: '#e8f8f5',
    borderRadius: 15,
    padding: 16,
    marginBottom: 12,
  },
  statsHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  mainStatsLabel: {
    fontSize: 12,
    color: '#117a65',
    fontWeight: 'bold',
  },
  mainStatsNumber: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#117a65',
  },
  mainStatsSub: {
    fontSize: 12,
    fontWeight: 'normal',
    color: '#16a085',
  },
  miniStatsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  miniStatsBox: {
    width: (width - 52) / 2,
    borderRadius: 12,
    padding: 14,
  },
  miniStatsHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 6,
  },
  miniStatsLabelText: {
    fontSize: 11,
    fontWeight: '600',
    marginLeft: 4,
  },
  miniStatsNumberText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2c3e50',
  },
  fabWrapper: {
    alignItems: 'flex-end',
    marginVertical: 10,
  },
  fabButton: {
    backgroundColor: '#d1f2eb',
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 1,
  },
  tabRow: {
    flexDirection: 'row',
    backgroundColor: '#eaeded',
    padding: 4,
    borderRadius: 20,
    marginBottom: 20,
  },
  tabButton: {
    flex: 1,
    paddingVertical: 8,
    alignItems: 'center',
    borderRadius: 18,
  },
  tabButtonActive: {
    backgroundColor: '#117a65',
  },
  tabButtonText: {
    fontSize: 13,
    color: '#7f8c8d',
    fontWeight: '600',
  },
  tabButtonTextActive: {
    color: '#ffffff',
  },
  monthGroupText: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#95a5a6',
    letterSpacing: 0.5,
    marginBottom: 15,
  },
  cardContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderRadius: 12,
    padding: 12,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#f2f4f4',
    elevation: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.03,
    shadowRadius: 5,
  },
  dateBox: {
    backgroundColor: '#f2f4f4',
    borderRadius: 10,
    width: 46,
    height: 46,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 15,
  },
  dateDayText: {
    fontSize: 9,
    color: '#95a5a6',
    fontWeight: 'bold',
  },
  dateNumberText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#7f8c8d',
  },
  infoBox: {
    flex: 1,
  },
  timeRangeText: {
    fontSize: 14,
    fontWeight: '700',
    color: '#2c3e50',
    marginBottom: 2,
  },
  kegiatanText: {
    fontSize: 12,
    color: '#34495e',
    marginBottom: 4,
  },
  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  locationText: {
    fontSize: 11,
    color: '#7f8c8d',
  },
  statusBadge: {
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: 15,
  },
  badgeSelesai: {
    backgroundColor: '#e8f8f5',
  },
  badgePulang: {
    backgroundColor: '#fdf2f2',
  },
  badgeTidakHadir: {
    backgroundColor: '#fbffda',
  },
  statusBadgeText: {
    fontSize: 11,
    fontWeight: 'bold',
  },
  textSelesai: {
    color: '#117a65',
  },
  textPulang: {
    color: '#c0392b',
  },
  textTidakHadir: {
    color: '#677207',
  },
  emptyText: {
    textAlign: 'center',
    color: '#95a5a6',
    marginTop: 30,
    fontSize: 13,
  },
});

export default styles;