<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $sujet }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:32px 16px;">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.10);">

        {{-- Header --}}
        @php
          $colors = [
            'rappel1'         => ['#f97316','#fff7ed'],
            'rappel2'         => ['#ef4444','#fef2f2'],
            'mise_en_demeure' => ['#1e293b','#f8fafc'],
          ];
          [$headerColor, $lightBg] = $colors[$type] ?? ['#696cff','#f0f1ff'];
          $icons = [
            'rappel1'         => '📋',
            'rappel2'         => '⚠️',
            'mise_en_demeure' => '⚖️',
          ];
          $typeLabels = [
            'rappel1'         => __('mail.recouvrement.type_rappel1'),
            'rappel2'         => __('mail.recouvrement.type_rappel2'),
            'mise_en_demeure' => __('mail.recouvrement.type_demeure'),
          ];
          $icon      = $icons[$type] ?? '📬';
          $typeLabel = $typeLabels[$type] ?? $sujet;
        @endphp
        <tr>
          <td style="background:{{ $headerColor }};padding:28px 36px;text-align:center;">
            <div style="font-size:36px;margin-bottom:8px;">{{ $icon }}</div>
            <h1 style="color:white;font-size:20px;font-weight:700;margin:0;line-height:1.3;">{{ $typeLabel }}</h1>
            <p style="color:rgba(255,255,255,.8);font-size:13px;margin:6px 0 0;">{{ __('mail.recouvrement.subtitle') }}</p>
          </td>
        </tr>

        {{-- Corps --}}
        <tr>
          <td style="background:white;padding:32px 36px;">
            <p style="color:#1e293b;font-size:15px;margin:0 0 16px;">{{ __('mail.greeting', ['name' => $nom]) }}</p>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px;">{{ $message }}</p>

            {{-- Montant encadré --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:{{ $lightBg }};border:2px solid {{ $headerColor }};border-radius:8px;margin-bottom:24px;">
              <tr>
                <td style="padding:16px 20px;text-align:center;">
                  <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:{{ $headerColor }};letter-spacing:.5px;margin-bottom:6px;">{{ __('mail.recouvrement.montant_label') }}</div>
                  <div style="font-size:28px;font-weight:800;color:{{ $headerColor }};">{{ format_price($dossier->montant_du - $dossier->montant_recouvre, $dossier->iddirection_ref ?? null) }}</div>
                  @if($dossier->nb_mois_impayes > 0)
                    <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ __('mail.recouvrement.mois_impayes', ['n' => $dossier->nb_mois_impayes]) }}</div>
                  @endif
                </td>
              </tr>
            </table>

            @if($type === 'mise_en_demeure')
            {{-- Avertissement mise en demeure --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fef2f2;border-left:4px solid #ef4444;border-radius:4px;margin-bottom:24px;">
              <tr>
                <td style="padding:14px 16px;">
                  <p style="color:#991b1b;font-size:13px;font-weight:700;margin:0 0 4px;">{{ __('mail.recouvrement.legal_title') }}</p>
                  <p style="color:#b91c1c;font-size:12px;margin:0;line-height:1.6;">
                    {{ __('mail.recouvrement.legal_body') }}
                  </p>
                </td>
              </tr>
            </table>
            @endif

            <p style="color:#64748b;font-size:13px;margin:0;">{{ __('mail.recouvrement.contact_note') }}</p>
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f8fafc;padding:20px 36px;border-top:1px solid #e2e8f0;text-align:center;">
            <p style="color:#94a3b8;font-size:11px;margin:0;line-height:1.6;">
              {{ __('mail.recouvrement.footer_text') }}<br>
              {{ __('mail.recouvrement.footer_no_reply') }}
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
