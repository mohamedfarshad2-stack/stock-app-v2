<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Spin & Win</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>

    <style>
        :root{
            --bg-1:#0b0f1a; --bg-2:#141a2a; --accent:#ff3d6e; --accent-2:#ffb703;
            --card:#111624; --text:#e8ecff; --muted:#a2a9c5;
            --shadow: 0 10px 40px rgba(0,0,0,.35);
            --glow: 0 0 30px rgba(255,61,110,.6), 0 0 60px rgba(255,61,110,.35);
        }
        *{box-sizing:border-box} html,body{height:100%}
        body{
            margin:0; font-family:Poppins,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; color:var(--text);
            background:
                radial-gradient(1200px 600px at 70% -10%, rgba(255,61,110,.12), transparent 60%),
                radial-gradient(900px 500px at -10% 20%, rgba(0,199,255,.12), transparent 60%),
                linear-gradient(180deg, var(--bg-1), var(--bg-2));
            overflow-x:hidden;
        }
        .wrap{max-width:1100px; margin:32px auto 64px; padding:0 20px; display:grid; grid-template-columns:1fr 320px; gap:32px}
        @media (max-width:960px){ .wrap{grid-template-columns:1fr} }
        .panel{ background:linear-gradient(180deg,#131a2b,#0d1220); border:1px solid rgba(255,255,255,.06); border-radius:20px; box-shadow:var(--shadow); position:relative }
        .panel-header{ padding:22px 24px; border-bottom:1px solid rgba(255,255,255,.06); display:flex; align-items:center; justify-content:space-between }
        .panel-header h1{ font-size:22px; margin:0; letter-spacing:.3px }
        .stage{ padding:28px 24px 32px; display:flex; flex-direction:column; align-items:center; gap:20px }

        .wheel-shell{ position:relative; width:380px; height:380px; display:grid; place-items:center; filter:drop-shadow(0 20px 50px rgba(0,0,0,.45)) }
        @media (max-width:520px){ .wheel-shell{ width:300px; height:300px } }
        .ring{ position:absolute; inset:-18px; border-radius:50%; background:
            radial-gradient(circle at 50% 50%, rgba(255,255,255,.18), transparent 60%),
            conic-gradient(from 0deg, rgba(255,61,110,.35), rgba(0,243,255,.35), rgba(255,187,0,.35), rgba(255,61,110,.35));
            filter:blur(14px); opacity:.7; pointer-events:none }
        .wheel-frame{ width:100%; height:100%; border-radius:50%; padding:12px; background:linear-gradient(180deg,#0b1120,#0b1020);
            box-shadow: inset 0 0 0 2px rgba(255,255,255,.06), 0 0 0 8px rgba(255,255,255,.02); position:relative }

        .wheel{
            width:100%; height:100%; border-radius:50%; overflow:hidden;
            background-color:#222; /* fallback */
            background-image: conic-gradient(
                #ff4d6d 0deg 45deg,
                #3ddc97 45deg 90deg,
                #2196f3 90deg 135deg,
                #ffb703 135deg 180deg,
                #b5179e 180deg 225deg,
                #00c2d1 225deg 270deg,
                #8bc34a 270deg 315deg,
                #e91e63 315deg 360deg
            );
            transition: transform 5s cubic-bezier(.12,.64,.15,1);
            box-shadow: inset 0 0 70px rgba(0,0,0,.35);
            position:relative;
        }
        /* slice separators */
        .wheel::after{
            content:""; position:absolute; inset:0;
            background-image: repeating-conic-gradient(
                rgba(255,255,255,.22) 0deg 0.7deg,
                transparent 0.7deg 45deg
            );
            pointer-events:none; mix-blend-mode:overlay;
        }

        .labels{ position:absolute; inset:0; display:block }
        .labels span{ position:absolute; left:50%; top:50%; transform-origin:0 0; width:50%; text-align:right; padding-right:18px; font-size:14px; font-weight:700; text-shadow:0 2px 4px rgba(0,0,0,.35) }
        .labels span:nth-child(1){ transform: rotate(22.5deg) translate(-50%,-50%) }
        .labels span:nth-child(2){ transform: rotate(67.5deg) translate(-50%,-50%) }
        .labels span:nth-child(3){ transform: rotate(112.5deg) translate(-50%,-50%) }
        .labels span:nth-child(4){ transform: rotate(157.5deg) translate(-50%,-50%) }
        .labels span:nth-child(5){ transform: rotate(202.5deg) translate(-50%,-50%) }
        .labels span:nth-child(6){ transform: rotate(247.5deg) translate(-50%,-50%) }
        .labels span:nth-child(7){ transform: rotate(292.5deg) translate(-50%,-50%) }
        .labels span:nth-child(8){ transform: rotate(337.5deg) translate(-50%,-50%) }

        .cap{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:96px; height:96px; border-radius:50%;
            background: radial-gradient(circle at 30% 30%, #fff, #d9e0ff 40%, #6a78ff 85%);
            border:4px solid rgba(255,255,255,.35); box-shadow:var(--glow), inset 0 0 20px rgba(0,0,0,.25);
            display:grid; place-items:center; color:#111; font-weight:800; font-size:13px; text-transform:uppercase; letter-spacing:.1em }

        .pointer{ position:absolute; top:-8px; left:50%; transform:translateX(-50%); width:0; height:0;
            border-left:16px solid transparent; border-right:16px solid transparent; border-bottom:28px solid var(--accent-2);
            filter:drop-shadow(0 4px 6px rgba(0,0,0,.45)) drop-shadow(0 0 10px rgba(255,183,3,.5));
            animation:pulse 1.8s infinite ease-in-out }
        @keyframes pulse{ 0%,100%{ transform:translateX(-50%) scale(1) } 50%{ transform:translateX(-50%) scale(1.06) } }

        .controls{ display:flex; flex-direction:column; gap:14px; width:100%; max-width:440px; margin:6px auto 0 }
        .spin-btn{ appearance:none; border:0; padding:16px 26px; border-radius:999px;
            background:linear-gradient(135deg,#ff4b2b,#ff416c); color:#fff; font-weight:800; letter-spacing:.04em; font-size:18px;
            box-shadow:var(--glow); cursor:pointer; transition:transform .18s, box-shadow .18s, filter .18s, opacity .18s }
        .spin-btn:hover{ transform:translateY(-1px) scale(1.02); filter:brightness(1.08) }
        .spin-btn:active{ transform:translateY(1px) scale(.98) }
        .spin-btn[disabled]{ opacity:.6; cursor:not-allowed; filter:grayscale(.2); box-shadow:none }

        .helper{ font-size:13px; color:var(--muted); text-align:center }
        .footnote{ text-align:center; font-size:12px; color:var(--muted); margin-top:10px }

        .side{ padding:22px 22px 26px }
        .card{ background:var(--card); border:1px solid rgba(255,255,255,.05); border-radius:16px; padding:16px 18px; box-shadow:var(--shadow) }
        .user{ display:flex; align-items:center; gap:12px }
        .avatar{ width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,#00e1ff,#6a78ff); display:grid; place-items:center; font-weight:800; color:#fff }
        .user .meta{ line-height:1.1 } .user .name{ font-weight:700 } .user .small{ font-size:12px; color:var(--muted) }
        .spacer{ height:16px }

        .history h3{ margin:0 0 10px; font-size:16px; letter-spacing:.3px }
        .history ul{ margin:0; padding:0; list-style:none; display:flex; flex-direction:column; gap:10px; max-height:360px; overflow:auto }
        .history li{ background:#0e1424; border:1px solid rgba(255,255,255,.05); padding:10px 12px; border-radius:12px; display:flex; align-items:center; justify-content:space-between; gap:10px }
        .tag{ font-size:12px; padding:4px 8px; border-radius:999px; background:rgba(255,255,255,.06); color:#cbd3ff; border:1px solid rgba(255,255,255,.09) }
        .used{ background:rgba(0,255,185,.1); color:#9fffe2; border-color:rgba(0,255,185,.25) }
        .notused{ background:rgba(255,107,53,.12); color:#ffd2c6; border-color:rgba(255,107,53,.25) }

        .modal{ position:fixed; inset:0; display:none; place-items:center; background:rgba(9,12,20,.65); backdrop-filter:blur(6px); z-index:50 }
        .modal.show{ display:grid }
        .modal-card{ width:min(92vw,520px); background: radial-gradient(120% 120% at 10% -10%, rgba(255,61,110,.14), transparent 40%), var(--card);
            border:1px solid rgba(255,255,255,.1); border-radius:20px; padding:22px 22px 18px; box-shadow:0 30px 80px rgba(0,0,0,.55); text-align:center; position:relative }
        .modal-card h2{ margin:8px 0 6px; font-size:24px }
        .prize{ font-size:28px; font-weight:800; letter-spacing:.3px; margin:12px 0 6px; text-shadow:0 4px 18px rgba(255,61,110,.35) }
        .modal-actions{ display:flex; gap:10px; justify-content:center; margin-top:16px }
        .btn{ padding:12px 18px; border-radius:12px; border:1px solid rgba(255,255,255,.1); cursor:pointer; background:#10172a; color:var(--text) }
        .btn.primary{ background:linear-gradient(135deg,#ff4b2b,#ff416c); color:#fff; border-color:transparent; box-shadow:var(--glow); font-weight:700 }
        .result{ text-align:center; font-size:18px; color:var(--muted); margin-top:4px }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="panel">
            <div class="panel-header">
                <h1>🎯 Spin & Win</h1>
                <div class="tag">Play • Win • Celebrate</div>
            </div>
            <div class="stage">
                <div class="wheel-shell">
                    <div class="pointer"></div>
                    <div class="ring"></div>
                    <div class="wheel-frame">
                        <div class="wheel" id="wheel">
                            <div class="labels">
                                <span>₹ / LKR Bonus</span>
                                <span>10% OFF</span>
                                <span>Free Gift</span>
                                <span>5% OFF</span>
                                <span>Try Again</span>
                                <span>20% OFF</span>
                                <span>Lucky Draw</span>
                                <span>Rs 1000</span>
                            </div>
                        </div>
                        <div class="cap">Spin</div>
                    </div>
                </div>
                <div class="controls">
                    <button id="spinBtn" class="spin-btn" onclick="spinWheel()">SPIN NOW</button>
                    <div class="helper"><span id="spinsLeft"></span></div>
                    <div id="inlineResult" class="result"></div>
                </div>
                <div class="footnote">By spinning, you agree to the promo terms.</div>
            </div>
        </div>

        <aside class="panel">
            <div class="side">
                <div class="card user">
                    <div class="avatar">{{ session('customer_name') ? strtoupper(substr(session('customer_name'),0,1)) : 'U' }}</div>
                    <div class="meta">
                        <div class="small">Welcome,</div>
                        <div class="name">{{ session('customer_name') ?? 'Guest' }}</div>
                    </div>
                </div>
                <div class="spacer"></div>
                <div class="card history">
                    <h3>🎁 Your Previous Rewards</h3>
                    <ul id="rewardsList"></ul>
                    <div id="historyEmpty" class="small" style="color:var(--muted); display:none;">No rewards yet — spin to win your first!</div>
                </div>
                 <!-- New: redeem contact line -->
                <div class="redeem-note small" style="margin-top:10px; color:var(--muted);">
                    To redeem, contact
                    <a href="tel:+94756666667" style="color:#fff; text-decoration:underline;">075 966 9669</a>
                </div>
            </div>
        </aside>
    </div>

    <div id="resultModal" class="modal">
        <div class="modal-card">
            <div class="tag" style="position:absolute; right:14px; top:14px;">Just Won</div>
            <h2>🎉 Congratulations!</h2>
            <div id="prizeText" class="prize">You won: —</div>
            <div class="modal-actions">
                <button class="btn" onclick="closeModal()">Close</button>
                <button class="btn primary" onclick="closeModal()">Use Now</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script>
        const initialRewards = @json(session('rewards') ?? []);
        document.addEventListener('DOMContentLoaded', () => { renderRewards(initialRewards); });

        let spinning = false;
        function spinWheel() {
            if (spinning) return; spinning = true;
            const btn = document.getElementById('spinBtn');
            const wheel = document.getElementById('wheel');
            const inlineResult = document.getElementById('inlineResult');
            btn.disabled = true; inlineResult.textContent = 'Spinning…';
            const endDeg = (3 + Math.floor(Math.random()*3))*360 + Math.floor(Math.random()*360);
            wheel.style.transform = `rotate(${endDeg}deg)`;
            fetch("{{ route('spin.api') }}", {
                method:'POST',
                headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
                body: JSON.stringify({})
            }).then(r=>r.json()).then(data=>{
                setTimeout(()=>{
                    if(data.blocked){
                        inlineResult.textContent=data.message||'⚠️ You cannot spin now.';
                        showResultModal(data.message||'Spin limit reached');
                        if(Array.isArray(data.rewards)) renderRewards(data.rewards);
                    }else{
                        const prize=data.reward||'Mystery Reward';
                        inlineResult.textContent=`🎉 You won: ${prize}`;
                        showResultModal(`You won: ${prize}`);
                        celebrate(); if(Array.isArray(data.rewards)) renderRewards(data.rewards);
                    }
                    if(typeof data.remaining_spins==='number'){
                        document.getElementById('spinsLeft').textContent=`Spins left today: ${data.remaining_spins}`;
                    }
                    btn.disabled=false; spinning=false;
                },5200);
            }).catch(()=>{
                setTimeout(()=>{
                    inlineResult.textContent='⚠️ Something went wrong. Please try again.';
                    btn.disabled=false; spinning=false;
                },5200);
            });
        }

        function renderRewards(list){
            const ul=document.getElementById('rewardsList');
            const empty=document.getElementById('historyEmpty');
            ul.innerHTML=''; if(!list||list.length===0){ empty.style.display='block'; return; }
            empty.style.display='none';
            list.forEach(item=>{
                const li=document.createElement('li');
                li.innerHTML=`<span>${escapeHTML(item.reward||'Unnamed Reward')}</span>
                    <span class="tag ${item.utilized?'used':'notused'}">${item.utilized?'Utilized':'Not Utilized'}</span>`;
                ul.appendChild(li);
            });
        }
        function showResultModal(txt){ document.getElementById('prizeText').textContent=txt; document.getElementById('resultModal').classList.add('show'); }
        function closeModal(){ document.getElementById('resultModal').classList.remove('show'); }
        function celebrate(){ confetti({particleCount:80,spread:70,origin:{y:.65}}); }
        function escapeHTML(str){return str.replace(/[&<>'"]/g,t=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[t]));}
    </script>
</body>
</html>
