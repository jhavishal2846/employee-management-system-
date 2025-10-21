# Employee Shift Management Functionality

## Schema Fixes
- [ ] Create new migration to fix employee_shifts status enum to include 'pending'
- [ ] Create BreakLog model

## Controller Updates
- [ ] Add acceptShift method in EmployeeController
- [ ] Add rejectShift method in EmployeeController
- [ ] Add clockIn method in EmployeeController
- [ ] Add clockOut method in EmployeeController
- [ ] Add startBreak method in EmployeeController
- [ ] Add endBreak method in EmployeeController
- [ ] Update attendance method to show clock in/out buttons

## Routes
- [ ] Add routes for accept/reject shifts
- [ ] Add routes for clock in/out
- [ ] Add routes for start/end break

## Views Updates
- [ ] Update employee/shifts/index.blade.php to add Accept/Reject buttons for pending shifts
- [ ] Update employee/attendance/index.blade.php to add Clock In/Out and Break buttons
- [ ] Add modal for rejection reason

## Overtime Calculation
- [ ] Update AttendanceLog model to calculate overtime on clock out (if total_hours > 8)
- [ ] Ensure break duration is subtracted from total hours

## Testing
- [ ] Test accept/reject functionality
- [ ] Test clock in/out
- [ ] Test break start/end
- [ ] Test overtime calculation
