<div style="width:100%;border:1px solid #1f2937;background:rgba(17,24,39,.92);border-radius:14px;padding:20px;">
    <div style="margin-bottom:14px;">
        <h3 style="margin:0;color:#fff;font-size:16px;font-weight:700;">Reservation Snapshot</h3>
        <p style="margin:6px 0 0;color:#94a3b8;font-size:12px;">Key indicators for the current booking flow.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px;">
        <article style="border:1px solid #263244;background:rgba(30,41,59,.7);border-radius:12px;padding:14px 15px;">
            <p style="margin:0;color:#94a3b8;font-size:12px;font-weight:600;">Total Reservations</p>
            <strong style="margin-top:4px;display:block;color:#fff;font-size:32px;line-height:1.05;font-weight:800;">{{ number_format($total) }}</strong>
        </article>

        <article style="border:1px solid #263244;background:rgba(30,41,59,.7);border-radius:12px;padding:14px 15px;">
            <p style="margin:0;color:#94a3b8;font-size:12px;font-weight:600;">Last 30 days</p>
            <strong style="margin-top:4px;display:block;color:#fff;font-size:32px;line-height:1.05;font-weight:800;">{{ number_format($last30) }}</strong>
        </article>

        <article style="border:1px solid #263244;background:rgba(30,41,59,.7);border-radius:12px;padding:14px 15px;">
            <p style="margin:0;color:#94a3b8;font-size:12px;font-weight:600;">Revenue (30d)</p>
            <strong style="margin-top:4px;display:block;color:#fff;font-size:32px;line-height:1.05;font-weight:800;">${{ number_format($revenue30, 2) }}</strong>
        </article>

        <article style="border:1px solid #263244;background:rgba(30,41,59,.7);border-radius:12px;padding:14px 15px;">
            <p style="margin:0;color:#94a3b8;font-size:12px;font-weight:600;">Confirmed</p>
            <strong style="margin-top:4px;display:block;color:#fff;font-size:32px;line-height:1.05;font-weight:800;">{{ number_format($confirmed) }}</strong>
        </article>
    </div>
</div>
