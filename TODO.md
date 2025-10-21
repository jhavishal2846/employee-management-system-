# Task: Make employee/attendance, employee/requests, and employee/profile pages design same as employee/shifts

## Overview
Update the design of employee pages to match the modern, custom-styled design of the shifts page, which includes:
- Custom CSS with variables, animations, and responsive design
- Page header with title and back button
- Dashboard card wrapper
- Custom table styling (for attendance and requests)
- Consistent badges, buttons, and empty states
- Responsive layout

## Steps
- [x] Update employee/attendance/index.blade.php
  - Replace Bootstrap classes with custom CSS from shifts page
  - Adapt page header to match shifts design
  - Style stats cards to fit the design (keep functionality)
  - Update table to use custom-table class and styling
  - Add empty state and pagination styling

- [x] Update employee/requests/index.blade.php
  - Replace Bootstrap classes with custom CSS from shifts page
  - Adapt page header to match shifts design
  - Update table to use custom-table class and styling
  - Add empty state and pagination styling

- [x] Update employee/profile/index.blade.php
  - Apply page header styling from shifts page
  - Style dashboard cards with custom CSS
  - Update badges and buttons to match design
  - Ensure responsive layout

## Dependent Files
- resources/views/employee/attendance/index.blade.php
- resources/views/employee/requests/index.blade.php
- resources/views/employee/profile/index.blade.php

## Followup Steps
- Test the pages for responsiveness and functionality
- Ensure no broken links or missing data
