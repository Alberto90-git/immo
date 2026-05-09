<?php
// Controller response messages (API JSON / flash)

return [
    'update_success'       => 'Update successful.',
    'update_fail'          => 'Failed, please try again.',
    'tenant_not_found'     => 'Tenant not found.',
    'no_data'              => 'No data',
    'fetch_tenants_error'  => 'Error retrieving tenants.',
    'access_denied'        => 'Access denied.',
    'config_saved'         => 'Configuration updated successfully.',
    'config_not_found'     => 'Configuration not found.',
    'at_config_saved'      => 'Africa\'s Talking configuration saved successfully.',
    'whatsapp_updated'     => 'WhatsApp number updated successfully.',

    // F7 — Advanced contracts
    'statut_bail_updated'  => 'Lease status updated.',
    'statut_bail_fail'     => 'Failed to update status.',
    'contrat_config_saved' => 'Contract template saved successfully.',
    'contrat_config_deleted' => 'Contract template deleted.',
    'contrat_config_fail'  => 'Failed, please try again.',

    // F11 — Prospect Pipeline
    'prospect_created'           => 'Prospect added successfully.',
    'prospect_updated'           => 'Prospect updated.',
    'prospect_deleted'           => 'Prospect deleted.',
    'prospect_statut_updated'    => 'Prospect status updated.',
    'prospect_converted'         => 'Prospect converted to tenant:',
    'prospect_public_success'    => 'Your request has been received. We will contact you shortly.',
    'visite_created'             => 'Visit scheduled successfully.',
    'visite_updated'             => 'Visit status updated.',
    'prereserv_created'          => 'Pre-reservation created successfully.',
    'prereserv_cancelled'        => 'Pre-reservation cancelled.',
    'prereserv_already_reserved' => 'This room is already pre-reserved for another prospect.',
    'prereserv_room_occupied'    => 'This room is not available (already occupied).',
];
