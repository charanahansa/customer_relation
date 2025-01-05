<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Management Dashboards
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Management\BreakdownController_Management;
use App\Http\Controllers\Management\NewInstallationController_Management;
use App\Http\Controllers\Management\ReInitializationController_Management;
use App\Http\Controllers\Management\SoftwareUpdationController_Management;
use App\Http\Controllers\Management\TerminalReplacementController_Management;
use App\Http\Controllers\Management\BackupRemovalController_Management;
use App\Http\Controllers\Management\ProfileSharingController_Management;
use App\Http\Controllers\Management\ProfileUpdationController_Management;
use App\Http\Controllers\Management\ProfileConversionController_Management;
use App\Http\Controllers\Management\MerchantRemovalController_Management;
use App\Http\Controllers\Management\TerminalInOutController_Management;
use App\Http\Controllers\Management\BaseSoftwareInstallationController_Management;
use App\Http\Controllers\Management\RepairControllerController_Management;

/*
|--------------------------------------------------------------------------
| Workflow Reports
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Reports\BreakdownReportController;
use App\Http\Controllers\Reports\NewInstallationReportController;
use App\Http\Controllers\Reports\ReInitilizationController;
use App\Http\Controllers\Reports\TerminalReplacementController;
use App\Http\Controllers\Reports\SoftwareUpdationController;
use App\Http\Controllers\Reports\ProfileSharingController;
use App\Http\Controllers\Reports\ProfileUpdationController;
use App\Http\Controllers\Reports\ProfileConversionController;
use App\Http\Controllers\Reports\MerchantRemovalController;
use App\Http\Controllers\Reports\TerminalInOutController;
use App\Http\Controllers\Reports\BaseSoftwareInstallationController;
use App\Http\Controllers\Reports\BackupRemovalController;

/*
|--------------------------------------------------------------------------
| Tmc Area
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Tmc\InOut\TerminalInNoteController;
use App\Http\Controllers\Tmc\InOut\TerminalOutNoteController;

use App\Http\Controllers\Tmc\Inquire\TerminalInNoteInquireController;
use App\Http\Controllers\Tmc\Inquire\TerminalOutNoteInquireController;
use App\Http\Controllers\Tmc\Inquire\TmcBinInquireController;

use App\Http\Controllers\Tmc\Delivery\DeliveryInquireController;
use App\Http\Controllers\Tmc\Delivery\DeliveryOrderController;
use App\Http\Controllers\Tmc\Delivery\DeliveryListController;

use App\Http\Controllers\Tmc\Hardware\Ticket\JobcardController;
use App\Http\Controllers\Tmc\Hardware\Ticket\JobcardSettingController;
use App\Http\Controllers\Tmc\Hardware\Ticket\JobcardCancelController;

use App\Http\Controllers\Tmc\Hardware\Inquire\JobCardInquireController as Tmc_JobCardInquireController;
use App\Http\Controllers\tmc\hardware\inquire\JobcardMultiInquireController as Tmc_JobcardMultiInquireController;
use App\Http\Controllers\tmc\hardware\inquire\QuotationInquireController as Tmc_QuotationInquireController;
use App\Http\Controllers\tmc\hardware\inquire\QuotationMultiInquireController as Tmc_QuotationMultiInquireController;

use App\Http\Controllers\Tmc\Hardware\Report\TerminalInReportController as Tmc_TerminalInReportController;
use App\Http\Controllers\Tmc\Hardware\Report\TerminalOutReportController as Tmc_TerminalOutReportController;
use App\Http\Controllers\Tmc\Hardware\Report\JobcardReportController as Tmc_JobcardReportController;

use App\Http\Controllers\Tmc\Backup\BackupReceiveNoteController;
use App\Http\Controllers\Tmc\Backup\Note\BackupRemoveNoteController;
use App\Http\Controllers\Tmc\Backup\Report\BRNReportController;
use App\Http\Controllers\Tmc\Backup\Report\BackupLocationReportController;
use App\Http\Controllers\Tmc\Backup\Inquire\BackupRemoveNoteInquireController;
use App\Http\Controllers\Tmc\Backup\BackupOperationController;

use App\Http\Controllers\Tmc\Courier\CourierInquireController;

/*
|--------------------------------------------------------------------------
| Hardware Area
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Hardware\Operation\Note\TerminalReleaseNoteController;

use App\Http\Controllers\Hardware\Dashboard\Operation\OperationDashboardOneController;
use App\Http\Controllers\Hardware\Dashboard\Operation\OperationDashboardTwoController;

use App\Http\Controllers\Hardware\Operation\Inquire\JobCardInquireController;
use App\Http\Controllers\Hardware\Operation\Inquire\JobcardMultiInquireController;
use App\Http\Controllers\Hardware\Quotation\Inquire\QuotationInquireController;
use App\Http\Controllers\Hardware\Quotation\Inquire\QuotationMultiInquireController;

use App\Http\Controllers\Hardware\Quotation\QuotationController;

use App\Http\Controllers\Hardware\SparePart\SparePartList\SparePartListController;
use App\Http\Controllers\Hardware\SparePart\SparePartList\SparePartRequestListController;
use App\Http\Controllers\Hardware\SparePart\SparePartRequestController;
use App\Http\Controllers\Hardware\SparePart\Note\SparePartAddController;
use App\Http\Controllers\Hardware\SparePart\Note\SparePartReceiveNoteController;
use App\Http\Controllers\Hardware\SparePart\Note\SparePartRequestNoteController;
use App\Http\Controllers\Hardware\SparePart\Note\SparePartIssueNoteController;
use App\Http\Controllers\Hardware\SparePart\SparePartList\SparePartReceiveListController;
use App\Http\Controllers\Hardware\SparePart\SparePartList\SparePartIssueListController;

use App\Http\Controllers\Hardware\SparePart\Report\SPBinReportController;
use App\Http\Controllers\Hardware\SparePart\Report\SPPendingReportController;
use App\Http\Controllers\Hardware\SparePart\Report\SPUsageReportController;

use App\Http\Controllers\Hardware\Quotation\Report\InsuaranceClaimReportController;

use App\Http\Controllers\Hardware\Operation\Report\TerminalInReportController;
use App\Http\Controllers\Hardware\Operation\Report\TerminalOutReportController;
use App\Http\Controllers\Hardware\Operation\Report\JobcardReportController;


/*
|--------------------------------------------------------------------------
| System Admin Area
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\System_Admin\Operation\IndividualEmailController;

use App\Http\Controllers\System_Admin\Master\BankController;
use App\Http\Controllers\System_Admin\Master\ModelController;
use App\Http\Controllers\System_Admin\Master\FaultController;
use App\Http\Controllers\System_Admin\Master\RelevantDetailController;
use App\Http\Controllers\System_Admin\Master\OfficerController;
use App\Http\Controllers\System_Admin\Master\CourierProviderController;
use App\Http\Controllers\System_Admin\Master\ActionTakenController;
use App\Http\Controllers\System_Admin\Master\SubStatusController;
use App\Http\Controllers\System_Admin\Master\ReInitializationReasonController;
use App\Http\Controllers\System_Admin\Master\BankOfficerController;
use App\Http\Controllers\System_Admin\Master\UserOfficerController;
use App\Http\Controllers\System_Admin\Master\ZoneController;

use App\Http\Controllers\System_Admin\List\MasterListController;

/*
|--------------------------------------------------------------------------
| Maintainance Area
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Maintainance\Note\MaintainanceAddNoteController;
use App\Http\Controllers\Maintainance\Note\MaintainanceRemoveNoteController;
use App\Http\Controllers\Maintainance\Inquire\MaintainanceAddInquireController;
use App\Http\Controllers\Maintainance\Inquire\MaintainanceRemoveInquireController;

/*
|--------------------------------------------------------------------------
| Field Service Area
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Field_Service\Ticketing_Pool\TicketAllocationPoolController;
use App\Http\Controllers\Field_Service\Field_Service_Allocation\FieldServiceAllocationController;

use App\Http\Controllers\Field_Service\Field_Service_Inquire\FsBreakdownInquireController;
use App\Http\Controllers\Field_Service\Field_Service_Inquire\FsNewInstallationInquireController;
use App\Http\Controllers\Field_Service\Field_Service_Inquire\FsReInitializationInquireController;
use App\Http\Controllers\Field_Service\Field_Service_Inquire\FsSoftwareUpdationInquireController;
use App\Http\Controllers\Field_Service\Field_Service_Inquire\FsTerminalReplacementInquireController;
use App\Http\Controllers\Field_Service\Field_Service_Inquire\FsBackupRemovalInquireController;

/*
|--------------------------------------------------------------------------
| Admin Flow
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/management_dashboard', [LoginController::class, 'Management_Dashboard'])->name('management_dashboard');
Route::any('/tmc_dashboard', [LoginController::class, 'Tmc_Dashboard'])->name('tmc_dashboard');
Route::any('/hardware_dashboard', [LoginController::class, 'Hardware_Dashboard'])->name('hardware_dashboard');
Route::any('/system_admin_dashboard', [LoginController::class, 'System_Admin_Dashboard'])->name('system_admin_dashboard');
Route::any('/maintainance_dashboard', [LoginController::class, 'Maintainance_Dashboard'])->name('maintainance_dashboard');
Route::any('/field_service_dashboard', [LoginController::class, 'Field_Service_Dashboard'])->name('field_service_dashboard');
Route::any('/vericentre_dashboard', [LoginController::class, 'Vericentre_Dashboard'])->name('vericentre_dashboard');


require __DIR__.'/auth.php';

/* ---------------------------------------------------------------- Administration ----------------------------------------------------------------------------------- */
//Password Reset
Route::get('password_reset', [PasswordResetController::class, 'index'])->name('password_reset');
Route::post('password_reset_process', [PasswordResetController::class, 'password_reset_process'])->name('password_reset_process');

/* ---------------------------------------------------------------- Management ----------------------------------------------------------------------------------- */
// Breakdown Dashboard
Route::get('breakdown_management_dashboard', [BreakdownController_Management::class, 'index'])->name('breakdown_management_dashboard');
Route::post('breakdown_management_process', [BreakdownController_Management::class, 'dashboard_breakdown_process'])->name('breakdown_management_process');
Route::get('breakdown_management_ajax_bank', [BreakdownController_Management::class, 'get_bank_wise_status_wise_breakdown_count'])->name('get_bank_wise_status_wise_breakdown_management_count');
Route::get('breakdown_management_ajax_status', [BreakdownController_Management::class, 'get_status_wise_bank_wise_breakdown_count'])->name('get_status_wise_bank_wise_breakdown_management_count');
Route::get('breakdown_management_ajax_detail', [BreakdownController_Management::class, 'get_breakdown_detail'])->name('get_breakdown_management_detail');


// New Installation Dashboard
Route::get('new_installation_managment_dashboard', [NewInstallationController_Management::class, 'index'])->name('new_installation_managment_dashboard');
Route::post('new_installation_managment_process', [NewInstallationController_Management::class, 'dashboard_new_installation_process'])->name('new_installation_managment_process');
Route::get('new_installation_managment_ajax_bank', [NewInstallationController_Management::class, 'get_bank_wise_status_wise_new_installation_count'])->name('get_bank_wise_status_wise_new_installation_managment_count');
Route::get('new_installation_managment_ajax_status', [NewInstallationController_Management::class, 'get_status_wise_bank_wise_new_installation_count'])->name('get_status_wise_bank_wise_new_installation_managment_count');
Route::get('new_installation_managment_ajax_detail', [NewInstallationController_Management::class, 'get_new_installation_detail'])->name('get_new_installation_managment_detail');

// Re Initilization Dashboard
Route::get('re_initialization_management_dashboard', [ReInitializationController_Management::class, 'index'])->name('re_initialization_management_dashboard');
Route::post('re_initialization_management_process', [ReInitializationController_Management::class, 'dashboard_re_initialization_process'])->name('re_initialization_management_process');
Route::get('re_initialization_management_ajax_bank', [ReInitializationController_Management::class, 'get_bank_wise_status_wise_re_initialization_count'])->name('get_bank_wise_status_wise_re_initialization_management_count');
Route::get('re_initialization_management_ajax_status', [ReInitializationController_Management::class, 'get_status_wise_bank_wise_re_initialization_count'])->name('get_status_wise_bank_wise_re_initialization_management_count');
Route::get('re_initialization_management_ajax_detail', [ReInitializationController_Management::class, 'get_re_initialization_detail'])->name('get_re_initialization_management_detail');

// Software Updation Dashboard
Route::get('software_updation_management_dashboard', [SoftwareUpdationController_Management::class, 'index'])->name('software_updation_management_dashboard');
Route::post('software_updation_management_process', [SoftwareUpdationController_Management::class, 'dashboard_software_updation_process'])->name('software_updation_management_process');
Route::get('software_updation_management_ajax_bank', [SoftwareUpdationController_Management::class, 'get_bank_wise_status_wise_software_updation_count'])->name('get_bank_wise_status_wise_software_updation_management_count');
Route::get('software_updation_management_ajax_status', [SoftwareUpdationController_Management::class, 'get_status_wise_bank_wise_software_updation_count'])->name('get_status_wise_bank_wise_software_updation_management_count');
Route::get('software_updation_management_ajax_detail', [SoftwareUpdationController_Management::class, 'get_software_updation_detail'])->name('get_software_updation_management_detail');

// Terminal Replacement Dashboard
Route::get('terminal_replacement_management_dashboard', [TerminalReplacementController_Management::class, 'index'])->name('terminal_replacement_management_dashboard');
Route::post('terminal_replacement_management_process', [TerminalReplacementController_Management::class, 'dashboard_terminal_replacement_process'])->name('terminal_replacement_management_process');
Route::get('terminal_replacement_management_ajax_bank', [TerminalReplacementController_Management::class, 'get_bank_wise_status_wise_terminal_replacement_count'])->name('get_bank_wise_status_wise_terminal_replacement_management_count');
Route::get('terminal_replacement_management_ajax_status', [TerminalReplacementController_Management::class, 'get_status_wise_bank_wise_terminal_replacement_count'])->name('get_status_wise_bank_wise_terminal_replacement_management_count');
Route::get('terminal_replacement_management_ajax_detail', [TerminalReplacementController_Management::class, 'get_terminal_replacement_detail'])->name('get_terminal_replacement_management_detail');

// Backup Removal Dashboard
Route::get('backup_removal_management_dashboard', [BackupRemovalController_Management::class, 'index'])->name('backup_removal_management_dashboard');
Route::post('backup_removal_management_process', [BackupRemovalController_Management::class, 'dashboard_backup_removal_process'])->name('backup_removal_management_process');
Route::get('backup_removal_management_ajax_bank', [BackupRemovalController_Management::class, 'get_bank_wise_status_wise_backup_removal_count'])->name('get_bank_wise_status_wise_backup_removal_management_count');
Route::get('backup_removal_management_ajax_status', [BackupRemovalController_Management::class, 'get_status_wise_bank_wise_backup_removal_count'])->name('get_status_wise_bank_wise_backup_removal_management_count');
Route::get('backup_removal_management_ajax_detail', [BackupRemovalController_Management::class, 'get_backup_removal_detail'])->name('get_backup_removal_management_detail');

// Profile Sharing Dashboard
Route::get('profile_sharing_management_dashboard', [ProfileSharingController_Management::class, 'index'])->name('profile_sharing_management_dashboard');
Route::post('profile_sharing_management_process', [ProfileSharingController_Management::class, 'dashboard_profile_sharing_process'])->name('profile_sharing_management_process');
Route::get('profile_sharing_management_ajax_bank', [ProfileSharingController_Management::class, 'get_bank_wise_status_wise_profile_sharing_count'])->name('get_bank_wise_status_wise_profile_sharing_management_count');
Route::get('profile_sharing_management_ajax_status', [ProfileSharingController_Management::class, 'get_status_wise_bank_wise_profile_sharing_count'])->name('get_status_wise_bank_wise_profile_sharing_management_count');
Route::get('profile_sharing_management_ajax_detail', [ProfileSharingController_Management::class, 'get_profile_sharing_detail'])->name('get_profile_sharing_management_detail');

// Profile Updation Dashboard
Route::get('profile_updation_management_dashboard', [ProfileUpdationController_Management::class, 'index'])->name('profile_updation_management_dashboard');
Route::post('profile_updation_management_process', [ProfileUpdationController_Management::class, 'dashboard_profile_updation_process'])->name('profile_updation_management_process');
Route::get('profile_updation_management_ajax_bank', [ProfileUpdationController_Management::class, 'get_bank_wise_status_wise_profile_updation_count'])->name('get_bank_wise_status_wise_profile_updation_management_count');
Route::get('profile_updation_management_ajax_status', [ProfileUpdationController_Management::class, 'get_status_wise_bank_wise_profile_updation_count'])->name('get_status_wise_bank_wise_profile_updation_management_count');
Route::get('profile_updation_management_ajax_detail', [ProfileUpdationController_Management::class, 'get_profile_updation_detail'])->name('get_profile_updation_management_detail');

// Profile Conversion Dashboard
Route::get('profile_conversion_management_dashboard', [ProfileConversionController_Management::class, 'index'])->name('profile_conversion_management_dashboard');
Route::post('profile_conversion_management_process', [ProfileConversionController_Management::class, 'dashboard_profile_conversion_process'])->name('profile_conversion_management_process');
Route::get('profile_conversion_management_ajax_bank', [ProfileConversionController_Management::class, 'get_bank_wise_status_wise_profile_conversion_count'])->name('get_bank_wise_status_wise_profile_conversion_management_count');
Route::get('profile_conversion_management_ajax_status', [ProfileConversionController_Management::class, 'get_status_wise_bank_wise_profile_conversion_count'])->name('get_status_wise_bank_wise_profile_conversion_management_count');
Route::get('profile_conversion_management_ajax_detail', [ProfileConversionController_Management::class, 'get_profile_conversion_detail'])->name('get_profile_conversion_management_detail');

// Merchant Removal Dashboard
Route::get('merchant_removal_management_dashboard', [MerchantRemovalController_Management::class, 'index'])->name('merchant_removal_management_dashboard');
Route::post('merchant_removal_management_process', [MerchantRemovalController_Management::class, 'dashboard_merchant_removal_process'])->name('merchant_removal_management_process');
Route::get('merchant_removal_management_ajax_bank', [MerchantRemovalController_Management::class, 'get_bank_wise_status_wise_merchant_removal_count'])->name('get_bank_wise_status_wise_merchant_removal_management_count');
Route::get('merchant_removal_management_ajax_status', [MerchantRemovalController_Management::class, 'get_status_wise_bank_wise_merchant_removal_count'])->name('get_status_wise_bank_wise_merchant_removal_management_count');
Route::get('merchant_removal_management_ajax_detail', [MerchantRemovalController_Management::class, 'get_merchant_removal_detail'])->name('get_merchant_removal_management_detail');

// Terminal In Out Dashboard
Route::get('terminal_in_out_management_dashboard', [TerminalInOutController_Management::class, 'index'])->name('terminal_in_out_management_dashboard');
Route::post('terminal_in_out_management_process', [TerminalInOutController_Management::class, 'dashboard_terminal_in_out_process'])->name('terminal_in_out_management_process');
Route::get('terminal_in_out_management_ajax_bank', [TerminalInOutController_Management::class, 'get_bank_wise_status_wise_terminal_in_out_count'])->name('get_bank_wise_status_wise_terminal_in_out_management_count');
Route::get('terminal_in_out_management_ajax_status', [TerminalInOutController_Management::class, 'get_status_wise_bank_wise_terminal_in_out_count'])->name('get_status_wise_bank_wise_terminal_in_out_management_count');
Route::get('terminal_in_out_management_ajax_detail', [TerminalInOutController_Management::class, 'get_terminal_in_out_detail'])->name('get_terminal_in_out_management_detail');

// Base software Installation Dashboard
Route::get('base_software_installation_management_dashboard', [BaseSoftwareInstallationController_Management::class, 'index'])->name('base_software_installation_management_dashboard');
Route::post('base_software_installation_management_process', [BaseSoftwareInstallationController_Management::class, 'dashboard_base_software_installation_process'])->name('base_software_installation_management_process');
Route::get('base_software_installation_management_ajax_bank', [BaseSoftwareInstallationController_Management::class, 'get_bank_wise_status_wise_base_software_installation_count'])->name('get_bank_wise_status_wise_base_software_installation_management_count');
Route::get('base_software_installation_management_ajax_status', [BaseSoftwareInstallationController_Management::class, 'get_status_wise_bank_wise_base_software_installation_count'])->name('get_status_wise_bank_wise_base_software_installation_management_count');
Route::get('base_software_installation_management_ajax_detail', [BaseSoftwareInstallationController_Management::class, 'get_base_software_installation_detail'])->name('get_base_software_installation_management_detail');

// Repair Ticket Dashboard
Route::get('Repair_management_Dashboard', [RepairControllerController_Management::class, 'index'])->name('Repair_management_Dashboard');
Route::post('repair_management_process', [RepairControllerController_Management::class, 'dashboard_repair_process'])->name('repair_management_process');


/* ---------------------------------------------------------------- Workflow Reports ----------------------------------------------------------------------------------- */
// Breakdown Report
Route::get('breakdown_report', [BreakdownReportController::class, 'index'])->name('breakdown_report');
Route::post('breakdown_report_process', [BreakdownReportController::class, 'breakdown_report_process'])->name('breakdown_report_process');

// New Installation Report
Route::get('new_installation_report', [NewInstallationReportController::class, 'index'])->name('new_installation_report');
Route::post('new_installation_report_process', [NewInstallationReportController::class, 'new_installation_report_process'])->name('new_installation_report_process');

// Re Initilization Report
Route::get('re_initilization_report', [ReInitilizationController::class, 'index'])->name('re_initilization_report');
Route::post('re_initilization_report_process', [ReInitilizationController::class, 're_initilization_report_process'])->name('re_initilization_report_process');

// Terminal Replacement Report
Route::get('terminal_replacement_report', [TerminalReplacementController::class, 'index'])->name('terminal_replacement_report');
Route::post('terminal_replacement_report_process', [TerminalReplacementController::class, 'terminal_replacement_report_process'])->name('terminal_replacement_report_process');

// Software Updation Report
Route::get('software_updation_report', [SoftwareUpdationController::class, 'index'])->name('software_updation_report');
Route::post('software_updation_report_process', [SoftwareUpdationController::class, 'software_updation_report_process'])->name('software_updation_report_process');

// Backup Removal Report
Route::get('backup_removal_report', [BackupRemovalController::class, 'index'])->name('backup_removal_report');
Route::post('backup_removal_report_process', [BackupRemovalController::class, 'backup_removal_report_process'])->name('backup_removal_report_process');

// Profile Sharing Report
Route::get('profile_sharing_report', [ProfileSharingController::class, 'index'])->name('profile_sharing_report');
Route::post('profile_sharing_report_process', [ProfileSharingController::class, 'profile_sharing_report_process'])->name('profile_sharing_report_process');

// Profile Updation Report
Route::get('profile_updation_report', [ProfileUpdationController::class, 'index'])->name('profile_updation_report');
Route::post('profile_updation_report_process', [ProfileUpdationController::class, 'profile_updation_report_process'])->name('profile_updation_report_process');

// Profile Conversion Report
Route::get('profile_conversion_report', [ProfileConversionController::class, 'index'])->name('profile_conversion_report');
Route::post('profile_conversion_report_process', [ProfileConversionController::class, 'profile_conversion_report_process'])->name('profile_conversion_report_process');

// Merchant Removal Report
Route::get('merchant_removal_report', [MerchantRemovalController::class, 'index'])->name('merchant_removal_report');
Route::post('merchant_removal_report_process', [MerchantRemovalController::class, 'merchant_removal_report_process'])->name('merchant_removal_report_process');

// Terminal In Out
Route::get('terminal_inout_report', [TerminalInOutController::class, 'index'])->name('terminal_inout_report');
Route::post('terminal_inout_report_process', [TerminalInOutController::class, 'terminal_inout_report_process'])->name('terminal_inout_report_process');

// Base Software Installation
Route::get('base_software_installation_report', [BaseSoftwareInstallationController::class, 'index'])->name('base_software_installation_report');
Route::post('base_software_installation_report_process', [BaseSoftwareInstallationController::class, 'base_software_installation_report_process'])->name('base_software_installation_report_process');

/* ---------------------------------------------------------------- TMC Area ----------------------------------------------------------------------------------- */

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Ticket
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
Route::get('terminal_in_note', [TerminalInNoteController::class, 'index'])->name('terminal_in_note');
Route::post('terminal_in_note_process', [TerminalInNoteController::class, 'terminal_in_note_process'])->name('terminal_in_note_process');
Route::post('terminal_in_note_print_document', [TerminalInNoteController::class, 'terminal_in_note_print_document'])->name('terminal_in_note_print_document');
Route::post('get_terminal_in_note', [TerminalInNoteController::class, 'getTerminalInNote'])->name('get_terminal_in_note');

Route::get('terminal_out_note', [TerminalOutNoteController::class, 'index'])->name('terminal_out_note');
Route::post('terminal_out_note_process', [TerminalOutNoteController::class, 'terminal_out_note_process'])->name('terminal_out_note_process');
Route::post('terminal_out_note_courier_slip', [TerminalOutNoteController::class, 'terminal_out_note_courier_slip'])->name('terminal_out_note_courier_slip');
Route::post('terminal_out_note_print_document', [TerminalOutNoteController::class, 'terminal_out_note_print_document'])->name('terminal_out_note_print_document');
Route::post('get_terminal_out_note', [TerminalOutNoteController::class, 'getTerminalOutNote'])->name('get_terminal_out_note');

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Inquire
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
Route::get('terminal_in_note_inquire', [TerminalInNoteInquireController::class, 'index'])->name('terminal_in_note_inquire');
Route::post('terminal_in_note_inquire_process', [TerminalInNoteInquireController::class, 'terminal_in_note_inquire_process'])->name('terminal_in_note_inquire_process');

Route::get('terminal_out_note_inquire', [TerminalOutNoteInquireController::class, 'index'])->name('terminal_out_note_inquire');
Route::post('terminal_out_note_inquire_process', [TerminalOutNoteInquireController::class, 'terminal_out_note_inquire_process'])->name('terminal_out_note_inquire_process');

Route::get('tmc_bin_inquire', [TmcBinInquireController::class, 'index'])->name('tmc_bin_inquire');
Route::post('tmc_bin_inquire_process', [TmcBinInquireController::class, 'tmcBinInquireProcess'])->name('tmc_bin_inquire_process');

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Delivery Process
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
Route::get('delivery_order', [DeliveryOrderController::class, 'index'])->name('delivery_order');
Route::post('delivery_order_process', [DeliveryOrderController::class, 'deliveryOrderProcess'])->name('delivery_order_process');

Route::get('delivery_list', [DeliveryListController::class, 'index'])->name('delivery_list');
Route::post('delivery_list_process', [DeliveryListController::class, 'deliveryListProcess'])->name('delivery_list_process');
Route::post('delivery_report', [DeliveryListController::class, 'deliveryReportProcess'])->name('delivery_report');

Route::get('delivery_inquire', [DeliveryInquireController::class, 'index'])->name('delivery_inquire');
Route::post('delivery_inquire_process', [DeliveryInquireController::class, 'deliveryInquireProcess'])->name('delivery_inquire_process');


/*
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Hardware
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/

// Job Card
Route::get('jobcard', [JobcardController::class, 'index'])->name('jobcard');
Route::post('jobcard_process', [JobcardController::class, 'jobcardProcess'])->name('jobcard_process');
Route::post('jobcard_print_document', [JobcardController::class, 'jobcardPrintDocument'])->name('jobcard_print_document');

// Job Card Setting
Route::get('jobcard_setting', [JobcardSettingController::class, 'index'])->name('jobcard_setting');
Route::post('jobcard_setting_process', [JobcardSettingController::class, 'jobcardSettingProcess'])->name('jobcard_setting_process');

// Job Card Cancellation
Route::get('jobcard_cancellation', [JobcardCancelController::class, 'index'])->name('jobcard_cancellation');
Route::post('jobcard_cancellation_process', [JobcardCancelController::class, 'jobcardCancelProcess'])->name('jobcard_cancellation_process');


//Job Card Inquire
Route::get('tmc_jobcard_inquire', [Tmc_JobCardInquireController::class, 'index'])->name('tmc_jobcard_inquire');
Route::post('tmc_jobcard_inquire_process', [Tmc_JobCardInquireController::class, 'tmc_jobcard_inquire_process'])->name('tmc_jobcard_inquire_process');

//Job Card Multi Inquire
Route::get('tmc_jobcard_multi_inquire', [Tmc_JobcardMultiInquireController::class, 'index'])->name('tmc_jobcard_multi_inquire');
Route::post('tmc_jobcard_multi_inquire_process', [Tmc_JobcardMultiInquireController::class, 'tmc_jobcard_multi_inquire_process'])->name('tmc_jobcard_multi_inquire_process');

//Quotation Inquire
Route::get('tmc_quotation_inquire', [Tmc_QuotationInquireController::class, 'index'])->name('tmc_quotation_inquire');
Route::post('tmc_quotation_inquire_process', [Tmc_QuotationInquireController::class, 'tmc_quotation_inquire_process'])->name('tmc_quotation_inquire_process');

//Quotation Multi Inquire
Route::get('tmc_quotation_multi_inquire', [Tmc_QuotationMultiInquireController::class, 'index'])->name('tmc_quotation_multi_inquire');
Route::post('tmc_quotation_multi_inquire_process', [Tmc_QuotationMultiInquireController::class, 'tmc_quotation_multi_inquire_process'])->name('tmc_quotation_multi_inquire_process');

//Hardware Operation Report
Route::get('tmc_terminal_in_report', [Tmc_TerminalInReportController::class, 'index'])->name('tmc_terminal_in_report');
Route::get('tmc_jobcard_report', [Tmc_JobcardReportController::class, 'index'])->name('tmc_jobcard_report');
Route::get('tmc_terminal_out_report', [Tmc_TerminalOutReportController::class, 'index'])->name('tmc_terminal_out_report');

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Backup Process
|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/

//Note
Route::get('backup_receive_note', [BackupReceiveNoteController::class, 'index'])->name('backup_receive_note');
Route::post('backup_receive_note_process', [BackupReceiveNoteController::class, 'backup_receive_note_process'])->name('backup_receive_note_process');

Route::get('backup_remove_note', [BackupRemoveNoteController::class, 'index'])->name('backup_remove_note');
Route::post('backup_remove_note_process', [BackupRemoveNoteController::class, 'backup_remove_note_process'])->name('backup_remove_note_process');
Route::get('brn_merchant_detail', [BackupRemoveNoteController::class, 'get_merchant_detail'])->name('brn_merchant_detail');
Route::post('get_backup_remove_note', [BackupRemoveNoteController::class, 'getBackupRemoveNote'])->name('get_backup_remove_note');

//Inquire
Route::get('backup_remove_note_inquire', [BackupRemoveNoteInquireController::class, 'index'])->name('backup_remove_note_inquire');
Route::post('backup_remove_note_inquire_process', [BackupRemoveNoteInquireController::class, 'backupRemoveNoteInquireProcess'])->name('backup_remove_note_inquire_process');

Route::get('backup_receive_note_list', [BackupReceiveNoteController::class, 'index'])->name('backup_receive_note_list');

//Report
Route::get('brn_report', [BRNReportController::class, 'index'])->name('brn_report');
Route::post('brn_report_process', [BRNReportController::class, 'brn_report_process'])->name('brn_report_process');

Route::get('backup_location_report', [BackupLocationReportController::class, 'index'])->name('backup_location_report');
Route::post('backup_location_report_process', [BackupLocationReportController::class, 'backup_location_report_process'])->name('backup_location_report_process');


// Backup Operation
Route::get('backup_operation', [BackupOperationController::class, 'index'])->name('backup_operation');
Route::post('backup_operation_process', [BackupOperationController::class, 'backup_operation_process'])->name('backup_operation_process');


//---------------- Courier Process
Route::get('courier_inquire', [CourierInquireController::class, 'index'])->name('courier_inquire');
Route::post('courier_inquire_process', [CourierInquireController::class, 'courier_inquire_process'])->name('courier_inquire_process');

/* ---------------------------------------------------------------- Hardware Area ----------------------------------------------------------------------------------- */

// ---------------- Operation
Route::get('terminal_release', [TerminalReleaseNoteController::class, 'index'])->name('terminal_release');
Route::post('terminal_release_process', [TerminalReleaseNoteController::class, 'terminal_release_process'])->name('terminal_release_process');
Route::post('remove_jobcards', [TerminalReleaseNoteController::class, 'removeJobcards'])->name('remove_jobcards');

// ---------------- Operation -> Report

Route::get('terminal_in_report', [TerminalInReportController::class, 'index'])->name('terminal_in_report');
Route::post('terminal_in_report_process', [TerminalInReportController::class, 'terminal_in_report_process'])->name('terminal_in_report_process');

Route::get('terminal_out_report', [TerminalOutReportController::class, 'index'])->name('terminal_out_report');
Route::post('terminal_out_report_process', [TerminalOutReportController::class, 'terminal_out_report_process'])->name('terminal_out_report_process');

Route::get('jobcard_report', [JobcardReportController::class, 'index'])->name('jobcard_report');
Route::post('jobcard_report_process', [JobcardReportController::class, 'jobcard_report_process'])->name('jobcard_report_process');

// ---------------- Spare Part

Route::get('spare_part_add_note', [SparePartAddController::class, 'index'])->name('spare_part_add_note');
Route::post('spare_part_add_individual_process', [SparePartAddController::class, 'spare_part_add_individual_process'])->name('spare_part_add_individual_process');

Route::get('spare_part_add_note_bulk', [SparePartAddController::class, 'load_bulk'])->name('spare_part_add_note_bulk');
Route::post('spare_part_add_process_bulk', [SparePartAddController::class, 'spare_part_add_process_bulk'])->name('spare_part_add_process_bulk');

Route::get('spare_part_receive_note', [SparePartReceiveNoteController::class, 'index'])->name('spare_part_receive_note');
Route::post('spare_part_receive_note_process', [SparePartReceiveNoteController::class, 'spare_part_receive_note_process'])->name('spare_part_receive_note_process');

Route::get('spare_part_request_note', [SparePartRequestNoteController::class, 'index'])->name('spare_part_request_note');
Route::post('spare_part_request_note_process', [SparePartRequestNoteController::class, 'spare_part_request_note_process'])->name('spare_part_request_note_process');
Route::get('get_spare_part_request_note', [SparePartRequestNoteController::class, 'get_spare_part_request_note'])->name('get_spare_part_request_note');
Route::post('spare_part_request_note_reject_process', [SparePartRequestNoteController::class, 'spare_part_request_note_reject_process'])->name('spare_part_request_note_reject_process');

Route::get('spare_part_issue_note', [SparePartIssueNoteController::class, 'index'])->name('spare_part_issue_note');
Route::post('spare_part_issue_note_process', [SparePartIssueNoteController::class, 'spare_part_issue_note_process'])->name('spare_part_issue_note_process');
Route::get('spare_part_issue_note_load/{request_id}', [SparePartIssueNoteController::class, 'spare_part_issue_note_load'])->name('spare_part_issue_note_load');


// ---------------- Spare Part List

Route::get('spare_part_list', [SparePartListController::class, 'index'])->name('spare_part_list');
Route::post('spare_part_list_process', [SparePartListController::class, 'spare_part_list_process'])->name('spare_part_list_process');

Route::get('spare_part_request_list', [SparePartRequestListController::class, 'index'])->name('spare_part_request_list');
Route::post('spare_part_request_list_process', [SparePartRequestListController::class, 'spare_part_request_list_process'])->name('spare_part_request_list_process');

Route::get('spare_part_request/{request_id}', [SparePartRequestController::class, 'index'])->name('spare_part_request');
Route::post('spare_part_request_process', [SparePartRequestController::class, 'spare_part_request_process'])->name('spare_part_request_process');

Route::get('spare_part_receive_list', [SparePartReceiveListController::class, 'index'])->name('spare_part_receive_list');
Route::post('spare_part_receive_list_process', [SparePartReceiveListController::class, 'spare_part_receive_list_process'])->name('spare_part_receive_list_process');

Route::get('get_sp_bin_qty', [SparePartRequestListController::class, 'get_sp_bin_qty'])->name('get_sp_bin_qty');

Route::get('spare_part_issue_list', [SparePartIssueListController::class, 'index'])->name('spare_part_issue_list');
Route::post('spare_part_issue_list_process', [SparePartIssueListController::class, 'spare_part_issue_list_process'])->name('spare_part_issue_list_process');

// ---------------------------- Spare Part Report

Route::get('sp_bin_report', [SPBinReportController::class, 'index'])->name('sp_bin_report');
Route::post('sp_bin_report_process', [SPBinReportController::class, 'sp_bin_report_process'])->name('sp_bin_report_process');

Route::get('sp_pending_report', [SPPendingReportController::class, 'index'])->name('sp_pending_report');
Route::post('sp_pending_report_process', [SPPendingReportController::class, 'SpPendingReportProcess'])->name('sp_pending_report_process');

Route::get('sp_usage_report', [SPUsageReportController::class, 'index'])->name('sp_usage_report');
Route::post('sp_usage_report_process', [SPUsageReportController::class, 'SpUsageReporProcess'])->name('sp_usage_report_process');


// ---------------- Operation
Route::get('jobcard_inquire', [JobCardInquireController::class, 'index'])->name('jobcard_inquire');
Route::post('jobcard_inquire_process', [JobCardInquireController::class, 'jobcard_inquire_process'])->name('jobcard_inquire_process');

Route::get('jobcard_multi_inquire', [JobcardMultiInquireController::class, 'index'])->name('jobcard_multi_inquire');
Route::post('jobcard_multi_inquire_process', [JobcardMultiInquireController::class, 'jobcard_multi_inquire_process'])->name('jobcard_multi_inquire_process');


// ---------------- Quotation
Route::get('quotation_inquire', [QuotationInquireController::class, 'index'])->name('quotation_inquire');
Route::post('quotation_inquire_process', [QuotationInquireController::class, 'quotation_inquire_process'])->name('quotation_inquire_process');

Route::get('quotation_multi_inquire', [QuotationMultiInquireController::class, 'index'])->name('quotation_multi_inquire');
Route::post('quotation_multi_inquire_process', [QuotationMultiInquireController::class, 'quotation_multi_inquire_process'])->name('quotation_multi_inquire_process');

// ---------------- Quotation
Route::get('quotation_inquire', [QuotationInquireController::class, 'index'])->name('quotation_inquire');
Route::post('quotation_inquire_process', [QuotationInquireController::class, 'quotation_inquire_process'])->name('quotation_inquire_process');
Route::get('quotation_number/{qt_no}',[QuotationController::class, 'load_quotation'])->name('Quotation.quotation_number');
Route::post('quotation_approve_process',[QuotationController::class,'quotation_approve_process'])->name('quotation_approve_process');

/*
	------------------------------------------------- Report ----------------------------------------------------------------------
*/
Route::get('insurance_claim_report', [InsuaranceClaimReportController::class, 'index'])->name('insurance_claim_report');
Route::post('insurance_claim_report_process', [InsuaranceClaimReportController::class, 'insurance_claim_report_process'])->name('insurance_claim_report_process');

/*
	------------------------------------------------- Dashboard ----------------------------------------------------------------------
*/
// Repair Ticket Dashboard
Route::get('repair_operation_dashboard_one', [OperationDashboardOneController::class, 'index'])->name('repair_operation_dashboard_one');
Route::post('repair_operation_dashboard_one_process', [OperationDashboardOneController::class, 'repair_operation_dashboard_one_process'])->name('repair_operation_dashboard_one_process');

Route::get('repair_operation_dashboard_two', [OperationDashboardTwoController::class, 'index'])->name('repair_operation_dashboard_two');
Route::post('repair_operation_dashboard_two_process', [OperationDashboardTwoController::class, 'repair_operation_dashboard_two_process'])->name('repair_operation_dashboard_two_process');
Route::get('repair_operation_dashboard_two_get_infor', [OperationDashboardTwoController::class, 'getInformation'])->name('repair_operation_dashboard_two_get_infor');


/* ---------------------------------------------------------------- System Admin Area ----------------------------------------------------------------------------------- */
// ---------------------
Route::get('individual_email', [IndividualEmailController::class, 'index'])->name('individual_email');
Route::post('individual_email_process', [IndividualEmailController::class, 'individual_email_process'])->name('individual_email_process');

Route::get('bank', [BankController::class, 'index'])->name('bank');
Route::post('bank_creation', [BankController::class, 'saveBank'])->name('bank_creation');
Route::get('bank_updation/{id}', [BankController::class, 'updateBank'])->name('bank_updation');

Route::get('model', [ModelController::class, 'index'])->name('model');
Route::post('model_creation', [ModelController::class, 'saveModel'])->name('model_creation');
Route::get('model_updation/{id}', [ModelController::class, 'updateModel'])->name('model_updation');

Route::get('fault', [FaultController::class, 'index'])->name('fault');
Route::post('fault_creation', [FaultController::class, 'saveFault'])->name('fault_creation');
Route::get('fault_updation/{id}', [FaultController::class, 'updateFault'])->name('fault_updation');

Route::get('relevant_detail', [RelevantDetailController::class, 'index'])->name('relevant_detail');
Route::post('relevant_detail_creation', [RelevantDetailController::class, 'saveRelevantDetail'])->name('relevant_detail_creation');
Route::get('relevant_detail_updation/{id}', [RelevantDetailController::class, 'updateRelevantDetail'])->name('relevant_detail_updation');

Route::get('action_taken', [ActionTakenController::class, 'index'])->name('action_taken');
Route::post('action_taken_creation', [ActionTakenController::class, 'saveActionTaken'])->name('action_taken_creation');
Route::get('action_taken_updation/{id}', [ActionTakenController::class, 'updateActionTaken'])->name('action_taken_updation');

Route::get('reinitialization_reason', [ReinitializationReasonController::class, 'index'])->name('reinitialization_reason');
Route::post('reinitialization_reason_creation', [ReinitializationReasonController::class, 'saveReason'])->name('reinitialization_reason_creation');
Route::get('reinitialization_reason_updation/{id}', [ReinitializationReasonController::class, 'updateReason'])->name('reinitialization_reason_updation');

Route::get('user_officer', [UserOfficerController::class, 'index'])->name('user_officer');
Route::post('user_officer/save', [UserOfficerController::class, 'saveData'])->name('user_officer.save');

Route::get('zone', [ZoneController::class, 'index'])->name('zone');
Route::post('zone_creation', [ZoneController::class, 'saveZone'])->name('zone_creation');
Route::get('zone_updation/{id}', [ZoneController::class, 'updateZone'])->name('zone_updation');


/*
---------------------------------------------------------- List ------------------------------------------------------------------
*/
Route::get('master_list', [MasterListController::class, 'index'])->name('master_list');


/* ---------------------------------------------------------------- Maintainance Area ----------------------------------------------------------------------------------- */
Route::get('maintainance_add',[MaintainanceAddNoteController::class, 'index'])->name('maintainance_add');
Route::post('maintainance_add_process',[MaintainanceAddNoteController::class, 'maintainance_add_process'])->name('maintainance_add_process');

Route::get('maintainance_add_inquire',[MaintainanceAddInquireController::class, 'index'])->name('maintainance_add_inquire');
Route::post('maintainance_add_inquire_process',[MaintainanceAddInquireController::class, 'maintainance_add_inquire_process'])->name('maintainance_add_inquire_process');

Route::get('maintainance_remove',[MaintainanceRemoveNoteController::class, 'index'])->name('maintainance_remove');
Route::post('maintainance_remove_process',[MaintainanceRemoveNoteController::class, 'maintainance_remove_process'])->name('maintainance_remove_process');

Route::get('maintainance_remove_inquire',[MaintainanceRemoveInquireController::class, 'index'])->name('maintainance_remove_inquire');
Route::post('maintainance_remove_inquire_process',[MaintainanceRemoveInquireController::class, 'maintainance_remove_inquire_process'])->name('maintainance_remove_inquire_process');

/* ---------------------------------------------------------------- Field Service Area ----------------------------------------------------------------------------------- */
Route::get('ticket_allocation_pool',[TicketAllocationPoolController::class, 'index'])->name('ticket_allocation_pool');
Route::get('get_ticket_allocation_data',[TicketAllocationPoolController::class, 'getTicketAllocationData'])->name('get_ticket_allocation_data');

Route::post('field_service_allocation',[FieldServiceAllocationController::class, 'getForm'])->name('field_service_allocation');
Route::post('field_service_allocation_process',[FieldServiceAllocationController::class, 'fieldServiceAllocationProcess'])->name('field_service_allocation_process');


Route::get('fs_breakdown_inquire',[FsBreakdownInquireController::class, 'index'])->name('fs_breakdown_inquire');
Route::post('fs_breakdown_inquire_process',[FsBreakdownInquireController::class, 'fsBreakdownInquireProcess'])->name('fs_breakdown_inquire_process');

Route::get('fs_new_inquire',[FsNewInstallationInquireController::class, 'index'])->name('fs_new_inquire');
Route::post('fs_new_inquire_process',[FsNewInstallationInquireController::class, 'fsNewInstallationInquireProcess'])->name('fs_new_inquire_process');

Route::get('fs_re_initialization_inquire',[FsReInitializationInquireController::class, 'index'])->name('fs_re_initialization_inquire');
Route::post('fs_re_initialization_inquire_process',[FsReInitializationInquireController::class, 'fsReInitializationInquireProcess'])->name('fs_re_initialization_inquire_process');

Route::get('fs_software_update_inquire',[FsSoftwareUpdationInquireController::class, 'index'])->name('fs_software_update_inquire');
Route::post('fs_software_update_inquire_process',[FsSoftwareUpdationInquireController::class, 'fsSoftwareUpdateInquireProcess'])->name('fs_software_update_inquire_process');

Route::get('fs_terminal_replacement_inquire',[FsTerminalReplacementInquireController::class, 'index'])->name('fs_terminal_replacement_inquire');
Route::post('fs_terminal_replacement_inquire_process',[FsTerminalReplacementInquireController::class, 'fsTerminalReplacementInquireProcess'])->name('fs_terminal_replacement_inquire_process');

Route::get('fs_backup_removal_inquire',[FsBackupRemovalInquireController::class, 'index'])->name('fs_backup_removal_inquire');
Route::post('fs_backup_removal_inquire_process',[FsBackupRemovalInquireController::class, 'fsBackupRemovalInquireProcess'])->name('fs_backup_removal_inquire_process');
