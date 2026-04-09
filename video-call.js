// video-call.js - Complete video call functionality

class VideoCall {
    constructor() {
        this.peer = null;
        this.localStream = null;
        this.currentCall = null;
        this.callId = null;
        this.isInitiator = false;
        this.callDuration = 0;
        this.timerInterval = null;
        this.remotePeerId = null;
    }

    async init(callId, remotePeerId = null, isInitiator = false) {
        this.callId = callId;
        this.remotePeerId = remotePeerId;
        this.isInitiator = isInitiator;
        
        // Create peer with unique ID
        this.peer = new Peer(this.callId, {
            config: {
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' },
                    { urls: 'stun:stun1.l.google.com:19302' },
                    { urls: 'stun:stun2.l.google.com:19302' }
                ]
            }
        });

        // Get user media
        try {
            this.localStream = await navigator.mediaDevices.getUserMedia({ 
                video: true, 
                audio: true 
            });
            const localVideo = document.getElementById('localVideo');
            if (localVideo) {
                localVideo.srcObject = this.localStream;
            }
            this.updateStatus('Camera and microphone ready');
        } catch (err) {
            console.error('Error accessing camera/mic:', err);
            this.showError('Unable to access camera or microphone. Please check permissions.');
            return;
        }

        // Peer event handlers
        this.peer.on('open', (id) => {
            console.log('Peer connected with ID:', id);
            this.updateStatus('Connected to server');
            
            if (this.isInitiator && this.remotePeerId) {
                this.initiateCall();
            } else if (!this.isInitiator) {
                this.waitForCall();
            }
        });

        this.peer.on('call', (call) => {
            this.answerCall(call);
        });

        this.peer.on('error', (err) => {
            console.error('Peer error:', err);
            this.showError('Connection error: ' + err.message);
        });

        this.peer.on('close', () => {
            console.log('Peer connection closed');
        });
    }

    initiateCall() {
        if (!this.remotePeerId) {
            this.showError('Doctor ID is required');
            return;
        }

        this.updateStatus('Calling doctor...');
        const call = this.peer.call(this.remotePeerId, this.localStream);
        this.setupCallEvents(call);
    }

    waitForCall() {
        this.updateStatus('Waiting for incoming call...');
        this.playNotification('waiting');
        
        this.peer.on('call', (call) => {
            this.showIncomingCall(call);
        });
    }

    answerCall(call) {
        call.answer(this.localStream);
        this.setupCallEvents(call);
        this.hideIncomingModal();
        this.updateStatus('Call connected');
        this.playNotification('connected');
    }

    showIncomingCall(call) {
        this.currentCall = call;
        const modal = document.getElementById('incomingCallModal');
        const callerName = document.getElementById('callerName');
        
        if (modal && callerName) {
            // Get caller info from server
            fetch(`get-caller-info.php?callId=${this.callId}`)
                .then(res => res.json())
                .then(data => {
                    callerName.textContent = data.name || 'Doctor';
                })
                .catch(() => {
                    callerName.textContent = 'Healthcare Provider';
                });
            
            modal.style.display = 'flex';
            this.playNotification('incoming');
            
            // Auto-answer after 15 seconds
            this.autoAnswerTimeout = setTimeout(() => {
                if (modal.style.display === 'flex') {
                    this.answerCall(call);
                }
            }, 15000);
        }
    }

    hideIncomingModal() {
        const modal = document.getElementById('incomingCallModal');
        if (modal) {
            modal.style.display = 'none';
        }
        if (this.autoAnswerTimeout) {
            clearTimeout(this.autoAnswerTimeout);
        }
    }

    setupCallEvents(call) {
        this.currentCall = call;
        
        call.on('stream', (remoteStream) => {
            const remoteVideo = document.getElementById('remoteVideo');
            if (remoteVideo) {
                remoteVideo.srcObject = remoteStream;
            }
            this.updateStatus('Connected - In consultation');
            this.startCallTimer();
            this.logCallStart();
        });

        call.on('close', () => {
            this.updateStatus('Call ended');
            this.endCall();
        });

        call.on('error', (err) => {
            console.error('Call error:', err);
            this.showError('Call error: ' + err.message);
            this.endCall();
        });
    }

    endCall() {
        if (this.currentCall) {
            this.currentCall.close();
        }
        if (this.localStream) {
            this.localStream.getTracks().forEach(track => track.stop());
        }
        if (this.peer) {
            this.peer.destroy();
        }
        this.stopCallTimer();
        this.logCallEnd();
        
        // Show end call message and redirect
        this.updateStatus('Call ended. Redirecting...');
        setTimeout(() => {
            window.location.href = 'call-history.php';
        }, 2000);
    }

    toggleMute() {
        if (this.localStream) {
            const audioTrack = this.localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                const muteBtn = document.getElementById('muteBtn');
                if (muteBtn) {
                    muteBtn.innerHTML = audioTrack.enabled ? '<i class="fas fa-microphone"></i>' : '<i class="fas fa-microphone-slash"></i>';
                    muteBtn.style.backgroundColor = audioTrack.enabled ? '#0077cc' : '#dc3545';
                }
            }
        }
    }

    toggleVideo() {
        if (this.localStream) {
            const videoTrack = this.localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = !videoTrack.enabled;
                const videoBtn = document.getElementById('videoBtn');
                if (videoBtn) {
                    videoBtn.innerHTML = videoTrack.enabled ? '<i class="fas fa-video"></i>' : '<i class="fas fa-video-slash"></i>';
                    videoBtn.style.backgroundColor = videoTrack.enabled ? '#0077cc' : '#dc3545';
                }
            }
        }
    }

    startCallTimer() {
        this.callStartTime = Date.now();
        this.timerInterval = setInterval(() => {
            this.callDuration = Math.floor((Date.now() - this.callStartTime) / 1000);
            const minutes = Math.floor(this.callDuration / 60);
            const seconds = this.callDuration % 60;
            const timerElement = document.getElementById('callTimer');
            if (timerElement) {
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    }

    stopCallTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }
    }

    async logCallStart() {
        try {
            await fetch('log-call.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    call_id: this.callId,
                    action: 'start',
                    timestamp: new Date().toISOString()
                })
            });
        } catch(e) { console.error('Failed to log call start:', e); }
    }

    async logCallEnd() {
        try {
            await fetch('log-call.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    call_id: this.callId,
                    action: 'end',
                    duration: this.callDuration,
                    timestamp: new Date().toISOString()
                })
            });
        } catch(e) { console.error('Failed to log call end:', e); }
    }

    playNotification(type) {
        try {
            const audio = new Audio();
            if (type === 'incoming') {
                audio.src = 'https://www.soundjay.com/misc/sounds/phone-ringing-01.mp3';
            } else if (type === 'connected') {
                audio.src = 'https://www.soundjay.com/misc/sounds/notification-01.mp3';
            } else {
                audio.src = 'https://www.soundjay.com/misc/sounds/notification-02.mp3';
            }
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch(e) {}
    }

    updateStatus(message) {
        const statusDiv = document.getElementById('callStatus');
        if (statusDiv) {
            statusDiv.textContent = message;
        }
        console.log('Call status:', message);
    }

    showError(message) {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
        console.error(message);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    window.videoCall = new VideoCall();
    
    const muteBtn = document.getElementById('muteBtn');
    const videoBtn = document.getElementById('videoBtn');
    const endCallBtn = document.getElementById('endCallBtn');
    
    if (muteBtn) muteBtn.onclick = () => window.videoCall.toggleMute();
    if (videoBtn) videoBtn.onclick = () => window.videoCall.toggleVideo();
    if (endCallBtn) endCallBtn.onclick = () => window.videoCall.endCall();
});