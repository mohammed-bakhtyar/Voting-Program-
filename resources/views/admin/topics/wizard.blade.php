@extends('layouts.main')

@section('content')
<style>
    /* Wizard Container & Transitions */
    .wizard-container {
        display: flex; flex-direction: column; min-height: 550px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        overflow: hidden;
    }
    @media (min-width: 768px) { .wizard-container { flex-direction: row; } }

    /* Left Sidebar */
    .wizard-sidebar {
        width: 100%; padding: 32px; flex-shrink: 0;
        background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    @media (min-width: 768px) { .wizard-sidebar { width: 280px; border-bottom: none; border-right: 1px solid rgba(255,255,255,0.05); } }

    .wizard-step-item { display: flex; items-align: start; position: relative; cursor: pointer; margin-bottom: 32px; }
    .wizard-step-item:last-child { margin-bottom: 0; }
    .step-line {
        position: absolute; top: 32px; left: 16px; width: 2px; height: calc(100% + 8px);
        background: rgba(255,255,255,0.08); z-index: 0;
    }
    .step-line.active { background: #6366f1; }
    
    .step-circle {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; z-index: 1;
        transition: all 0.3s;
    }
    .step-circle.current { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; box-shadow: 0 0 0 4px rgba(99,102,241,0.2); }
    .step-circle.completed { background: #6366f1; color: white; }
    .step-circle.upcoming { background: rgba(255,255,255,0.05); color: #64748b; border: 1px solid rgba(255,255,255,0.1); }
    
    .step-text { margin-left: 16px; padding-top: 6px; }
    .step-title { font-size: 14px; font-weight: 600; transition: color 0.2s; }
    .step-title.current { color: #e2e8f0; }
    .step-title.completed { color: #94a3b8; }
    .step-title.upcoming { color: #475569; }

    /* Main Content */
    .wizard-content { flex: 1; padding: 40px; display: flex; flex-direction: column; }
    .step-header { margin-bottom: 32px; }
    .step-title-text { font-size: 24px; font-weight: 800; color: #f8fafc; letter-spacing: -0.5px; margin-bottom: 6px; }
    .step-sub-text { font-size: 14px; color: #64748b; }

    /* Forms */
    .form-group { margin-bottom: 24px; max-width: 600px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #64748b; margin-bottom: 8px; }
    .form-input, .form-textarea {
        width: 100%;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px; padding: 14px 16px; color: #f8fafc;
        font-size: 14px; font-family: 'Inter', sans-serif;
        outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, .form-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.15); }
    .form-input::placeholder, .form-textarea::placeholder { color: #475569; }

    /* Toggles */
    .toggle-box {
        background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px; padding: 20px; margin-bottom: 16px; max-width: 600px;
    }
    .toggle-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; cursor: pointer; }
    .toggle-row:last-child { margin-bottom: 0; }
    .toggle-check { margin-top: 3px; accent-color: #6366f1; width: 16px; height: 16px; }
    .toggle-info { flex: 1; }
    .toggle-info-title { font-size: 14px; font-weight: 600; color: #cbd5e1; }
    .toggle-info-desc { font-size: 13px; color: #64748b; margin-top: 2px; }

    /* Options List */
    .option-item {
        display: flex; align-items: center; gap: 12px; margin-bottom: 12px; max-width: 600px;
        position: relative;
    }
    .option-input-wrap { flex: 1; position: relative; }
    .option-num {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: 13px; font-weight: 700; color: #475569; pointer-events: none;
    }
    .option-item input { padding-left: 36px; }
    
    /* VIP Toggle */
    .vip-toggle-btn {
        display: flex; align-items: center; gap: 6px;
        padding: 10px 14px; border-radius: 10px;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;
        cursor: pointer; transition: all 0.2s;
    }
    .vip-toggle-btn.active {
        background: rgba(251,191,36,0.1); border-color: rgba(251,191,36,0.3);
        color: #fbbf24;
    }
    .vip-toggle-btn:hover { background: rgba(255,255,255,0.06); }
    .vip-toggle-btn.active:hover { background: rgba(251,191,36,0.15); }
    
    .remove-opt-btn {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #64748b; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        cursor: pointer; transition: all 0.2s;
    }
    .remove-opt-btn:hover { color: #ef4444; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); }

    .add-opt-btn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; max-width: 600px; padding: 14px; border-radius: 12px;
        background: rgba(99,102,241,0.05); color: #818cf8;
        border: 1px dashed rgba(99,102,241,0.3); font-size: 14px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; margin-top: 8px;
    }
    .add-opt-btn:hover { background: rgba(99,102,241,0.1); border-color: rgba(99,102,241,0.5); }

    /* Review Info */
    .review-card {
        background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px; padding: 24px; max-width: 600px;
    }
    .review-row { display: flex; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .review-row:last-child { border-bottom: none; padding-bottom: 0; }
    .review-row:first-child { padding-top: 0; }
    .review-label { font-size: 13px; color: #64748b; }
    .review-val { font-size: 14px; font-weight: 600; color: #e2e8f0; text-align: right; }

    /* Footer Buttons */
    .wizard-footer {
        padding: 24px 40px; background: rgba(0,0,0,0.2);
        border-top: 1px solid rgba(255,255,255,0.05);
        display: flex; justify-content: space-between; align-items: center;
    }
    .wiz-btn {
        padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; border: none; font-family: 'Inter', sans-serif;
    }
    .wiz-btn-back { background: transparent; color: #64748b; }
    .wiz-btn-back:hover { color: #e2e8f0; }
    .wiz-btn-draft { background: rgba(255,255,255,0.05); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); }
    .wiz-btn-draft:hover { background: rgba(255,255,255,0.08); }
    .wiz-btn-next { background: #f8fafc; color: #0f172a; }
    .wiz-btn-next:hover { opacity: 0.9; transform: translateY(-1px); }
    .wiz-btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
    .wiz-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); }

    [x-cloak] { display: none !important; }
</style>

<div class="mb-8">
    <h1 class="vp-section-title">Create New Poll</h1>
    <p class="vp-section-sub">Configure your new voting topic and its options.</p>
</div>

<div class="wizard-container" x-data="topicWizard()" x-cloak>
    
    <!-- Sidebar -->
    <div class="wizard-sidebar">
        <template x-for="(title, idx) in Object.values(stepTitles)" :key="idx">
            <div class="wizard-step-item" @click="if((idx+1) <= Math.max(step, highestStepReached)) step = idx+1">
                <div x-show="idx < 3" class="step-line" :class="step > (idx+1) ? 'active' : ''"></div>
                
                <div class="step-circle" :class="{
                    'current': step === (idx+1),
                    'completed': step > (idx+1),
                    'upcoming': step < (idx+1)
                }">
                    <span x-show="step <= (idx+1)" x-text="idx+1"></span>
                    <svg x-show="step > (idx+1)" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <div class="step-text">
                    <div class="step-title" :class="{
                        'current': step === (idx+1),
                        'completed': step > (idx+1),
                        'upcoming': step < (idx+1)
                    }" x-text="title"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col">
        <div class="wizard-content">
            
            <!-- STEP 1 -->
            <div x-show="step === 1" x-transition.opacity.duration.300ms class="flex-1">
                <div class="step-header">
                    <h3 class="step-title-text" x-text="stepTitles[1]"></h3>
                    <p class="step-sub-text">Provide the fundamental information for your poll.</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Topic Title <span style="color:#ef4444;">*</span></label>
                    <input type="text" x-model="formData.title" class="form-input" placeholder="e.g. Which framework do you prefer?">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea x-model="formData.description" rows="3" class="form-textarea" placeholder="Enter detailed description..."></textarea>
                </div>

                <div class="toggle-box">
                    <label class="toggle-row">
                        <input type="checkbox" x-model="formData.allow_multiple_votes" class="toggle-check">
                        <div class="toggle-info">
                            <div class="toggle-info-title">Allow multiple votes</div>
                            <div class="toggle-info-desc">Users can select more than one option.</div>
                        </div>
                    </label>
                    <label class="toggle-row">
                        <input type="checkbox" x-model="formData.show_results_before_voting" class="toggle-check">
                        <div class="toggle-info">
                            <div class="toggle-info-title">Show results upfront</div>
                            <div class="toggle-info-desc">Display current percentages before the user votes.</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- STEP 2 -->
            <div x-show="step === 2" x-transition.opacity.duration.300ms class="flex-1" style="display:none;">
                <div class="step-header">
                    <h3 class="step-title-text" x-text="stepTitles[2]"></h3>
                    <p class="step-sub-text">Add the choices users can vote for, and optionally highlight a VIP choice.</p>
                </div>

                <div>
                    <template x-for="(option, index) in formData.options" :key="index">
                        <div class="option-item">
                            <div class="option-input-wrap">
                                <span class="option-num" x-text="index + 1"></span>
                                <input type="text" x-model="option.text" class="form-input" placeholder="Option text...">
                            </div>
                            
                            <!-- VIP Toggle -->
                            <button type="button" 
                                    @click="option.is_vip = !option.is_vip"
                                    class="vip-toggle-btn"
                                    :class="option.is_vip ? 'active' : ''"
                                    title="Make this option a VIP choice">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                VIP
                            </button>

                            <button @click="removeOption(index)" type="button" class="remove-opt-btn" x-show="formData.options.length > 2" title="Remove option">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </template>
                    
                    <button @click="addOption()" type="button" class="add-opt-btn" x-show="formData.options.length < 10">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Add Another Option
                    </button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div x-show="step === 3" x-transition.opacity.duration.300ms class="flex-1" style="display:none;">
                <div class="step-header">
                    <h3 class="step-title-text" x-text="stepTitles[3]"></h3>
                    <p class="step-sub-text">Double check your poll settings before publishing.</p>
                </div>

                <div class="review-card">
                    <div class="review-row">
                        <div class="review-label">Topic Title</div>
                        <div class="review-val" x-text="formData.title || 'Not set'"></div>
                    </div>
                    <div class="review-row">
                        <div class="review-label">Options Count</div>
                        <div class="review-val" style="color:#818cf8;" x-text="formData.options.filter(o => o.text.trim() !== '').length"></div>
                    </div>
                    <div class="review-row">
                        <div class="review-label">VIP Options</div>
                        <div class="review-val" style="color:#fbbf24;" x-text="formData.options.filter(o => o.is_vip && o.text.trim() !== '').length"></div>
                    </div>
                    <div class="review-row">
                        <div class="review-label">Settings</div>
                        <div class="review-val" style="font-weight:400; font-size:13px; color:#94a3b8;">
                            <div style="margin-bottom:4px;">
                                <span x-show="formData.allow_multiple_votes" style="color:#10b981;">✓</span>
                                <span x-show="!formData.allow_multiple_votes" style="color:#ef4444;">✕</span>
                                Multiple Votes
                            </div>
                            <div>
                                <span x-show="formData.show_results_before_voting" style="color:#10b981;">✓</span>
                                <span x-show="!formData.show_results_before_voting" style="color:#ef4444;">✕</span>
                                Show Results Upfront
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4 (Publish) -->
            <div x-show="step === 4" x-transition.opacity.duration.300ms class="flex-1 flex flex-col items-center justify-center text-center" style="display:none; min-height:300px;">
                
                <template x-if="isPublishing">
                    <div>
                        <svg class="animate-spin" style="width:48px;height:48px;color:#6366f1;margin:0 auto 16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div style="font-size:20px;font-weight:700;color:#f8fafc;">Publishing your poll...</div>
                        <p style="color:#64748b;font-size:14px;margin-top:8px;">Please do not close this window.</p>
                    </div>
                </template>

                <template x-if="!isPublishing && publishSuccess">
                    <div>
                        <div style="width:80px;height:80px;border-radius:50%;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                            <svg width="40" height="40" style="color:#10b981;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <h3 style="font-size:28px;font-weight:800;color:#f8fafc;">Poll is live!</h3>
                        <p style="color:#94a3b8;font-size:15px;margin-top:8px;">Your topic has been successfully published.</p>
                        <div style="margin-top:32px;display:flex;gap:12px;justify-content:center;">
                            <a href="{{ route('admin.dashboard') }}" class="wiz-btn wiz-btn-draft" style="text-decoration:none;">Dashboard</a>
                            <a :href="publishedUrl" class="wiz-btn wiz-btn-primary" style="text-decoration:none;">View Poll</a>
                        </div>
                    </div>
                </template>

                <template x-if="!isPublishing && !publishSuccess">
                    <div>
                        <div style="width:80px;height:80px;border-radius:50%;background:rgba(239,68,68,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                            <svg width="40" height="40" style="color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 style="font-size:24px;font-weight:800;color:#f8fafc;">Publishing Failed</h3>
                        <div x-text="errorMessage" style="color:#fca5a5;background:rgba(239,68,68,0.1);padding:12px 16px;border-radius:10px;margin:16px auto;font-size:14px;max-width:400px;"></div>
                        <button @click="submitPoll()" class="wiz-btn wiz-btn-primary" style="margin-top:8px;">Try Again</button>
                    </div>
                </template>

            </div>
        </div>

        <!-- Footer -->
        <div class="wizard-footer" x-show="step < 4">
            <button type="button" @click="step--" class="wiz-btn wiz-btn-back" :style="step === 1 ? 'visibility:hidden;' : ''">Back</button>
            <div style="display:flex; gap:12px;">
                <button type="button" @click="saveDraft()" class="wiz-btn wiz-btn-draft" x-show="step < 3">Save Draft</button>
                <button type="button" @click="nextStep()" class="wiz-btn wiz-btn-next" x-show="step < 3">Continue</button>
                <button type="button" @click="submitPoll()" class="wiz-btn wiz-btn-primary" x-show="step === 3">Publish Poll</button>
            </div>
        </div>
    </div>
</div>

<script>
function topicWizard() {
    return {
        step: 1,
        highestStepReached: 1,
        stepTitles: { 1: 'Basic Information', 2: 'Voting Options', 3: 'Review & Confirm', 4: 'Publish Poll' },
        formData: {
            title: '', description: '', allow_multiple_votes: false, show_results_before_voting: true, status: 'active',
            options: [ { text: '', is_vip: false }, { text: '', is_vip: false } ]
        },
        isPublishing: false, publishSuccess: false, publishedUrl: '#', errorMessage: '',

        addOption() {
            if (this.formData.options.length < 10) this.formData.options.push({ text: '', is_vip: false });
        },
        removeOption(index) {
            if (this.formData.options.length > 2) this.formData.options.splice(index, 1);
        },
        nextStep() {
            if (this.step < 4) {
                if (this.step === 1 && !this.formData.title) return alert('Please enter a Topic Title.');
                if (this.step === 2) {
                    const valid = this.formData.options.filter(o => o.text.trim() !== '');
                    if (valid.length < 2) return alert('Please provide at least two valid options.');
                }
                this.step++;
                if (this.step > this.highestStepReached) this.highestStepReached = this.step;
            }
        },
        async saveDraft() {
            this.formData.status = 'draft';
            await this.submitPoll(true);
        },
        async submitPoll(isDraft = false) {
            if (!isDraft) this.step = 4;
            this.isPublishing = true;
            this.publishSuccess = false;
            
            const validOptions = this.formData.options.filter(o => o.text.trim() !== '').map(o => ({
                text: o.text.trim(),
                is_vip: o.is_vip
            }));
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('{{ route("admin.topics.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        title: this.formData.title, description: this.formData.description,
                        allow_multiple_votes: this.formData.allow_multiple_votes, show_results_before_voting: this.formData.show_results_before_voting,
                        status: this.formData.status, options: validOptions,
                        opens_at: new Date().toISOString(), closes_at: new Date(Date.now() + 365*24*60*60*1000).toISOString()
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (!isDraft) {
                        this.publishSuccess = true;
                        this.publishedUrl = `/topics/${data.id}`;
                    } else {
                        alert('Draft saved successfully!');
                        this.isPublishing = false;
                    }
                } else {
                    const errorData = await response.json();
                    this.errorMessage = errorData.message || 'Validation failed';
                    this.publishSuccess = false;
                }
            } catch (e) {
                this.errorMessage = e.message;
                this.publishSuccess = false;
            }
            this.isPublishing = false;
        }
    }
}
</script>
@endsection
