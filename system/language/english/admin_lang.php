<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['alert-login_failed'] = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> Log-In Failed!</h5>Wrong Username / Password!</div>';

$lang['alert-kicked'] = '<div class="alert alert-danger dark alert-dismissible fade show" role="alert"><h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>Please login first!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

$lang['alert-error'] = '<div class="alert alert-danger alert-dismissible mx-3"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> %1$s</h5>%2$s</div>';

$lang['alert-body-error'] = '<div class="alert alert-danger alert-dismissible mx-3"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> Errors!</h5>%1$s</div>';

$lang['alert-insert_success'] = '<div class="alert alert-success alert-dismissible mx-3"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon icon fas fa-check"></i> Data Executed Successfully!</h5>%1$s Data Has Been Successfully Added To The Database!</div>';

$lang['alert-updated_success'] = '<div class="alert alert-success alert-dismissible mx-3"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon icon fas fa-check"></i> Data Executed Successfully!</h5>%1$s Data in Database has been successfully changed!</div>';

$lang['alert-delete_success'] = '<div class="alert alert-warning alert-dismissible mx-3"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon fas fa-check"></i> Data Successfully Deleted!</h5>%1$s data has been successfully deleted from the database!</div>';

$lang['alert-failed'] = '<div class="alert alert-danger alert-dismissible mx-3"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon fas fa-exclamation-triangle"></i> Data Failed To Execute!</h5>There is an error in the query, please check again!</div>';

$lang['message-error_page']        = 'An Error Occurred in Your Account! Please contact the Admin for more info!';
$lang['modal-delete_confirmation'] = 'Are you sure you want to delete this %1$s?';
$lang['text-login']                = 'Log-In';
$lang['text-logout']               = 'Log-Out';
$lang['text-dashboard']            = 'Dashboard';
$lang['text-save']                 = 'Simpan';
$lang['text-confirm']              = 'Confirm';
$lang['text-find_data']            = 'Find Data';
$lang['text-update']               = 'Update';
$lang['text-delete']               = 'Delete';
$lang['text-cancel']               = 'Cancel';
$lang['text-error']                = 'Error!';
$lang['text-back']                 = 'Back';
$lang['text-act']                  = 'Action';
$lang['text-input_file']           = 'Choose file';
$lang['text-search_data']          = 'Search Data';
$lang['text-upload_photo']         = 'Upload Foto';
$lang['text-change_password']      = 'Change Password';
$lang['text-old_password']         = 'Old Password';
$lang['text-new_password']         = 'Change Password';
$lang['text-confirm_password']     = 'Change Password';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-users']        = 'Users';
$lang['table-update-users'] = $lang['text-update'] . ' ' . $lang['table-users'];
$lang['table-delete-users'] = $lang['text-delete'] . ' ' . $lang['table-users'];

$lang['field-user_fullname'] = 'User Fullname';
$lang['field-username']      = 'Username';
$lang['field-password']      = 'Password';
$lang['field-user_access']   = 'User Access';
$lang['field-user_photo']    = 'User Photo';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-guests']        = 'Guests';
$lang['table-update-guests'] = $lang['text-update'] . ' ' . $lang['table-guests'];
$lang['table-delete-guests'] = $lang['text-delete'] . ' ' . $lang['table-guests'];
$lang['text-guests-data']    = $lang['table-guests'] . ' Data';

$lang['field-guest_name']      = 'Guest Name';
$lang['field-identity']        = 'Identity';
$lang['field-identity_type']   = 'Identity Type';
$lang['field-identity_number'] = 'Identity Number';
$lang['field-national']        = 'National';
$lang['field-birth_date']      = 'Birth Date';
$lang['field-guest_address']   = 'Guest Address';
$lang['field-phone_number']    = 'Phone Number';
$lang['field-email']           = 'Email';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-floors']        = 'Floors';
$lang['table-update-floors'] = $lang['text-update'] . ' ' . $lang['table-floors'];
$lang['table-delete-floors'] = $lang['text-delete'] . ' ' . $lang['table-floors'];

$lang['field-floor_name'] = 'Floor Name';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-payments']        = 'Payments';
$lang['table-update-payments'] = $lang['text-update'] . ' ' . $lang['table-payments'];
$lang['table-delete-payments'] = $lang['text-delete'] . ' ' . $lang['table-payments'];

$lang['field-payment_name'] = 'Payment Name';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-room_types']        = 'Room Types';
$lang['table-update-room_types'] = $lang['text-update'] . ' ' . $lang['table-room_types'];
$lang['table-delete-room_types'] = $lang['text-delete'] . ' ' . $lang['table-room_types'];
$lang['text-room_types-data']    = $lang['table-guests'] . ' Data';

$lang['field-room_type_name'] = 'Room Types Name';
$lang['text-total_rooms']     = 'Total Rooms';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-rooms']        = 'Rooms';
$lang['table-update-rooms'] = $lang['text-update'] . ' ' . $lang['table-rooms'];
$lang['table-delete-rooms'] = $lang['text-delete'] . ' ' . $lang['table-rooms'];

$lang['field-room_number'] = 'Room Number';
$lang['text-room_type']    = 'Room Type';
$lang['text-floor']        = 'Floor';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-sessions']        = 'Sessions';
$lang['table-update-sessions'] = $lang['text-update'] . ' ' . $lang['table-sessions'];
$lang['table-delete-sessions'] = $lang['text-delete'] . ' ' . $lang['table-sessions'];
$lang['text-sessions-data']    = $lang['table-sessions'] . ' Data';

$lang['field-session_name']  = 'Session Name';
$lang['field-start_session'] = 'Start Session';
$lang['field-end_session']   = 'End Session';
$lang['text-session_length'] = 'Session Length';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-segments']        = 'Segments';
$lang['table-update-segments'] = $lang['text-update'] . ' ' . $lang['table-segments'];
$lang['table-delete-segments'] = $lang['text-delete'] . ' ' . $lang['table-segments'];

$lang['field-segment_name'] = 'Segment Name';
$lang['field-segment_type'] = 'Segment Type';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-room_plans']        = 'Room Plans';
$lang['table-update-room_plans'] = $lang['text-update'] . ' ' . $lang['table-room_plans'];
$lang['table-delete-room_plans'] = $lang['text-delete'] . ' ' . $lang['table-room_plans'];

$lang['field-room_plan_name'] = 'Room Plan Name';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-requests']        = 'Requests';
$lang['table-update-requests'] = $lang['text-update'] . ' ' . $lang['table-requests'];
$lang['table-delete-requests'] = $lang['text-delete'] . ' ' . $lang['table-requests'];

$lang['field-request_name']  = 'Request Name';
$lang['field-request_price'] = 'Request Price';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$lang['table-room_rates']        = 'Room Rates';
$lang['table-update-room_rates'] = $lang['text-update'] . ' ' . $lang['table-room_rates'];
$lang['table-delete-room_rates'] = $lang['text-delete'] . ' ' . $lang['table-room_rates'];
$lang['text-room_rates-data']    = $lang['table-room_rates'] . ' Data';

$lang['field-room_rate_name'] = 'Room Plan Name';
$lang['field-room_price']     = 'Room Price';
$lang['text-session']         = 'Session';
$lang['text-room_plan']       = 'Room Plan';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$lang['text-rooms']              = 'Rooms';
$lang['text-guests']             = 'Guests';
$lang['text-guest_name']         = 'Guest Name';
$lang['text-vacant']             = 'Vacant';
$lang['text-occupied']           = 'Occupied';
$lang['text-expected_departure'] = 'Due Out / Expected Departure';
$lang['text-expected_arrival']   = 'Expected Arrival';
$lang['text-VR']                 = 'Vacant Ready';
$lang['text-VC']                 = 'Vacant Clean';
$lang['text-VD']                 = 'Vacant Dirty';
$lang['text-OC']                 = 'Occupied Clean';
$lang['text-OD']                 = 'Occupied Dirty';
$lang['text-OO']                 = 'Out of Service';


$lang['text-reservation_report']      = 'Reservation Report';
$lang['text-housekeeping_day_report'] = 'Housekeeping Day Report';
$lang['text-date_report']             = 'Date Report';

$lang['text-toast-error'] = 'There is an error!';


$lang['text-room_reservation'] = 'Room Reservation';
$lang['text-add_reservation']  = 'Add Reservation';
$lang['text-checkin']          = 'Check-In';
$lang['text-checkout']         = 'Check-Out';

$lang['text-find_rooms'] = 'Find Room';

$lang['text-checkin_checkout_empty']  = 'Complete the data first!';
$lang['text-checkin_checkout_same']   = 'check-in and check-out can\'t be the same!';
$lang['text-checkin_checkout_bigger'] = 'Check-In Date Must not be higher than Check-Out Date!';

$lang['text-rooms_data']   = 'Rooms Data';
$lang['text-choose_rooms'] = 'Choose Rooms';


$lang['text-room_occupied']       = 'Room Occupied';
$lang['text-room_no_room_plans']  = 'Room Type Does not have a Price on this Room Plan!';
$lang['text-room_out_of_service'] = 'Room on Out Of Service!';
$lang['text-room_no_choose']      = 'Select Room Data first!';


$lang['text-reservation_fill']        = 'Reservation Fill';
$lang['text-reservation']             = 'Reservation';
$lang['text-add_guests']              = 'Add Guests';
$lang['text-additional_costs']        = 'Add Additional Costs';
$lang['text-payment']                 = 'Payment';
$lang['text-segment']                 = 'Segment';
$lang['text-deposit']                 = 'Deposit';
$lang['text-checkin_time']            = 'Check-In Time';
$lang['text-checkout_time']           = 'Check-Out Time';
$lang['text-adult_guest']             = 'Adult Guest';
$lang['text-child_guest']             = 'Child Guest';
$lang['text-people']                  = 'People';
$lang['text-long_stay']               = 'Long Stay';
$lang['text-in_house']                = 'In House';
$lang['text-reservation_description'] = 'Service / Product Description';
$lang['text-price']                   = 'Price';
$lang['text-qty']                     = 'QTY';
$lang['text-total']                   = 'Total';
$lang['text-night']                   = 'Night';
$lang['text-total_price']             = 'Total Price';
$lang['text-reservation_empty']       = 'Complete the data first!';
$lang['toast-add_reservation']        = 'Reservation Data Has Been Successfully Added To The Database!';

$lang['toast-add_guests'] = 'Guest Data Has Been Successfully Added To The Database!';
$lang['text-guest_empty'] = 'Complete the data first!';
$lang['text-guest_same']  = 'Identity Number in this Identity Type has been used!';

$lang['text-additional_cost_type']     = 'Cost Type';
$lang['text-request']                  = 'Request';
$lang['text-discount']                 = 'Discount';
$lang['text-loss_or_damage']           = 'Loss or Damage';
$lang['text-additional_costs_empty']   = 'Complete the data first!';
$lang['toast-add_additional_costs']    = 'Additional Costs Data Has Been Successfully Added!';
$lang['toast-delete_additional_costs'] = 'Additional Costs Data Has Been Successfully Deleted!';



$lang['payment-deposit'] = 'Deposit';

$lang['text-print_folio'] = 'Print Folio';


$lang['text-reservation_data']     = 'Reservation Data';
$lang['text-reservation_detail']   = 'Reservation Detail';
$lang['text-reservation_number']   = 'Reservation Number';
$lang['text-payment_number']       = 'Payment Number';
$lang['text-payment_type']       = 'Payment Type';
$lang['text-invoice_number']       = 'Invoice Number';
$lang['text-bill_number']          = 'Bill Number';
$lang['text-total_guest']          = 'Total Guest';
$lang['text-reservation_time']     = 'Reservation Time';
$lang['text-adult']                = 'Adult';
$lang['text-child']                = 'Child';
$lang['text-cancel_reservation']   = 'Cancel Reservation';
$lang['text-checkin_reservation']  = 'Check-In Reservation';
$lang['text-checkout_reservation'] = 'Check-Out Reservation';


$lang['text-checkin_reservation_empty'] = 'Complete the data first!';
$lang['toast-add_checkin_reservation']  = 'Reservation has been Successfully check-In!';


$lang['modal-cancel_reservation'] = 'Are You Sure to Cancel This Reservation?';
$lang['toast-cancel_reservation'] = 'Reservation has been Successfully Canceled!';


$lang['text-delete_additional_costs']       = 'Delete Additional Costs';
$lang['modal-delete_additional_costs']      = 'Are You Sure to Delete This Additional Costs?';
$lang['text-delete_additional_costs_empty'] = 'Complete the data first!';



$lang['text-checkin_data']   = 'Check-In Data';
$lang['text-checkin_detail'] = 'Check-In Detail';
$lang['text-change_rooms']   = 'Change Rooms';

$lang['text-change_price']       = 'Change Price Rooms';
$lang['text-change_price_same']  = 'Price can\'t be the same!';
$lang['text-change_price_empty'] = 'Complete the data first!';
$lang['toast-change_price']      = 'Room Reservation Price has been Successfully Changed!';


$lang['text-add_payment']         = 'Add Payments';
$lang['text-payment_date']        = 'Payment Date';
$lang['text-total_payment']       = 'Total Payment';
$lang['text-remaining_payment']   = 'Remaining Payment';
$lang['text-payment_description'] = 'Payment Description';
$lang['text-add_payment_empty']   = 'Complete the data first!';
$lang['toast-add_payment']        = 'Payment has been Successfully Added!';

$lang['text-delete_payment']       = 'Delete Payment';
$lang['text-delete_payment_empty'] = 'Complete the data first!';
$lang['modal-delete_payment']      = 'Are You Sure to Delete This Payment?';
$lang['toast-delete_payment']      = 'Payment has been Successfully Deleted!';


$lang['text-change_rooms']             = 'Change Rooms';
$lang['text-check_change_rooms']       = 'Check Extend Days';
$lang['text-check_rooms_change_empty'] = 'Complete the data first!';
$lang['toast-change_rooms']            = 'Rooms has been Successfully Changed!';


$lang['text-extend']            = 'Extend';
$lang['text-extend_days']       = 'Extend Days';
$lang['text-check_price']       = 'Check Price';
$lang['text-extend_days_empty'] = 'Complete the data first!';
$lang['text-extend_days_same']  = 'Date Can\'t Be same!';

$lang['text-check_extend_days']       = 'Check Extend Days';
$lang['text-check_extend_days_empty'] = 'Complete the data first!';


$lang['text-receipt']                    = 'Receipt';
$lang['text-bill']                       = 'Bill';
$lang['text-invoice']                    = 'Invoice';
$lang['text-remaining_return']           = 'Remaining Return';
$lang['text-checkout_reservation_empty'] = 'Complete the data first!';
$lang['toast-checkout_reservation']      = 'Resercation has been Successfully Check-Out!';



$lang['text-room_status']         = 'Room Status';
$lang['text-change_status']       = 'Change Status';
$lang['text-room_status_empty']   = 'Complete the data first!';
$lang['toast-room_status_change'] = 'Room Status has been Successfully Changed!';



$lang['text-nightaudit_report']    = 'Night Audit Report';
$lang['text-nightaudit_modal1']    = 'Pada Tanggal ';
$lang['text-nightaudit_modal2']    = ' Belum dikonfirmasi, apa anda ingin mengkonfirmasi?';
$lang['text-cleaning_description'] = 'Cleaning Description';
$lang['text-description']          = 'description';


$lang['text-daily_shift_report'] = 'Daily Shift Report';
$lang['text-shift']              = 'Shift';


$lang['text-guest_history']      = 'Guest History';
$lang['text-guest_data']         = 'Guest Data';
$lang['text-reservation_status'] = 'Reservation Status';
$lang['text-guest_data_empty']   = 'Complete the data first!';
$lang['text-guest_detail']       = 'Guest Detail';
$lang['text-print_billinvoice']  = 'Print Bill / Invoice';



$lang['text-date']  = 'Date';
$lang['text-deposit_refund']  = 'Deposit Refund';
$lang['text-housekeeping_room_report']  = 'Housekeeping Room Report';
$lang['text-housekeeping_room_change_report']  = 'Housekeeping Room Change Report';


$lang['text-room_status_data']  = 'Room Status Data';
