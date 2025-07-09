

<?php $__env->startSection('title', __('settings.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto" x-data="settingsForm()">
    
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
            <?php echo e(__('settings.title')); ?>

        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            <?php echo e(__('settings.subtitle')); ?>

        </p>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        
        <div class="hidden sm:block border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    <?php echo e(__('settings.tabs.general')); ?>

                </button>
                
                <button @click="activeTab = 'contact'" 
                        :class="activeTab === 'contact' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-address-book mr-2"></i>
                    <?php echo e(__('settings.tabs.contact')); ?>

                </button>
                
                <button @click="activeTab = 'localization'" 
                        :class="activeTab === 'localization' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-globe mr-2"></i>
                    <?php echo e(__('settings.tabs.localization')); ?>

                </button>
                
                <button @click="activeTab = 'subscription'" 
                        :class="activeTab === 'subscription' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-crown mr-2"></i>
                    <?php echo e(__('settings.tabs.subscription')); ?>

                </button>
            </nav>
        </div>

        
        <div class="sm:hidden p-4 border-b border-gray-200 dark:border-gray-700">
            <label for="mobile-tabs" class="sr-only"><?php echo e(__('settings.select_tab')); ?></label>
            <select id="mobile-tabs" 
                    x-model="activeTab"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                <option value="general"><?php echo e(__('settings.tabs.general')); ?></option>
                <option value="contact"><?php echo e(__('settings.tabs.contact')); ?></option>
                <option value="localization"><?php echo e(__('settings.tabs.localization')); ?></option>
                <option value="subscription"><?php echo e(__('settings.tabs.subscription')); ?></option>
            </select>
        </div>

        
        <form @submit.prevent="submitForm" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div x-show="activeTab === 'general'" x-cloak>
                <div class="space-y-6">
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <?php echo e(__('settings.sections.organization_info')); ?>

                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'name','label' => __('settings.fields.organization_name'),'value' => $tenant->name,'placeholder' => __('settings.placeholders.organization_name'),'hint' => __('settings.hints.organization_name'),'xModel' => 'form.name','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.organization_name')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->name),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.organization_name')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.organization_name')),'x-model' => 'form.name','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'legal_name','label' => __('settings.fields.legal_name'),'value' => $tenant->legal_name,'placeholder' => __('settings.placeholders.legal_name'),'hint' => __('settings.hints.legal_name'),'xModel' => 'form.legal_name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'legal_name','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.legal_name')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->legal_name),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.legal_name')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.legal_name')),'x-model' => 'form.legal_name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'code','label' => __('settings.fields.organization_code'),'value' => $tenant->code,'hint' => __('settings.hints.organization_code'),'disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'code','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.organization_code')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->code),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.organization_code')),'disabled' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'tenant_id','label' => __('settings.fields.organization_id'),'value' => $tenant->id,'hint' => __('settings.hints.organization_id'),'disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'tenant_id','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.organization_id')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->id),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.organization_id')),'disabled' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="activeTab === 'contact'" x-cloak>
                <div class="space-y-6">
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <?php echo e(__('settings.sections.contact_info')); ?>

                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'email','name' => 'email','label' => __('settings.fields.email'),'value' => $tenant->email,'placeholder' => __('settings.placeholders.email'),'hint' => __('settings.hints.email'),'xModel' => 'form.email','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'email','name' => 'email','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.email')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->email),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.email')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.email')),'x-model' => 'form.email','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal75e480475b71ebe2632250788db3a069 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75e480475b71ebe2632250788db3a069 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.specialized.phone-input','data' => ['name' => 'phone','label' => __('settings.fields.phone'),'value' => $tenant->phone,'hint' => __('settings.hints.phone'),'xModel' => 'form.phone','countries' => $countries]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.specialized.phone-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'phone','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.phone')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->phone),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.phone')),'x-model' => 'form.phone','countries' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($countries)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75e480475b71ebe2632250788db3a069)): ?>
<?php $attributes = $__attributesOriginal75e480475b71ebe2632250788db3a069; ?>
<?php unset($__attributesOriginal75e480475b71ebe2632250788db3a069); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75e480475b71ebe2632250788db3a069)): ?>
<?php $component = $__componentOriginal75e480475b71ebe2632250788db3a069; ?>
<?php unset($__componentOriginal75e480475b71ebe2632250788db3a069); ?>
<?php endif; ?>
                            </div>

                            
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'url','name' => 'website','label' => __('settings.fields.website'),'value' => $tenant->website,'placeholder' => __('settings.placeholders.website'),'hint' => __('settings.hints.website'),'xModel' => 'form.website']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'url','name' => 'website','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.website')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->website),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.website')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.website')),'x-model' => 'form.website']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <?php echo e(__('settings.sections.address')); ?>

                        </h3>
                        <div class="space-y-4">
                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'address_line_1','label' => __('settings.fields.address_line_1'),'value' => $tenant->address_line_1,'placeholder' => __('settings.placeholders.address_line_1'),'hint' => __('settings.hints.address_line_1'),'xModel' => 'form.address_line_1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'address_line_1','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.address_line_1')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->address_line_1),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.address_line_1')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.address_line_1')),'x-model' => 'form.address_line_1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'address_line_2','label' => __('settings.fields.address_line_2'),'value' => $tenant->address_line_2,'placeholder' => __('settings.placeholders.address_line_2'),'hint' => __('settings.hints.address_line_2'),'xModel' => 'form.address_line_2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'address_line_2','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.address_line_2')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->address_line_2),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.address_line_2')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.address_line_2')),'x-model' => 'form.address_line_2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'city','label' => __('settings.fields.city'),'value' => $tenant->city,'placeholder' => __('settings.placeholders.city'),'hint' => __('settings.hints.city'),'xModel' => 'form.city']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'city','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.city')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->city),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.city')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.city')),'x-model' => 'form.city']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                </div>

                                
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'state','label' => __('settings.fields.state'),'value' => $tenant->state,'placeholder' => __('settings.placeholders.state'),'hint' => __('settings.hints.state'),'xModel' => 'form.state']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'state','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.state')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->state),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.state')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.state')),'x-model' => 'form.state']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                </div>

                                
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['name' => 'postal_code','label' => __('settings.fields.postal_code'),'value' => $tenant->postal_code,'placeholder' => __('settings.placeholders.postal_code'),'hint' => __('settings.hints.postal_code'),'xModel' => 'form.postal_code']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'postal_code','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.postal_code')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->postal_code),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.postal_code')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.postal_code')),'x-model' => 'form.postal_code']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                </div>

                                
                                <div>
                                    <?php if (isset($component)) { $__componentOriginal5272b2ca8b6e42e14bdac2834e9f5537 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5272b2ca8b6e42e14bdac2834e9f5537 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.specialized.country-select','data' => ['name' => 'country_id','label' => __('settings.fields.country'),'value' => $tenant->country_id,'placeholder' => __('settings.placeholders.select_country'),'hint' => __('settings.hints.country'),'countries' => $countries,'xModel' => 'form.country_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.specialized.country-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'country_id','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.country')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->country_id),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.select_country')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.country')),'countries' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($countries),'x-model' => 'form.country_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5272b2ca8b6e42e14bdac2834e9f5537)): ?>
<?php $attributes = $__attributesOriginal5272b2ca8b6e42e14bdac2834e9f5537; ?>
<?php unset($__attributesOriginal5272b2ca8b6e42e14bdac2834e9f5537); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5272b2ca8b6e42e14bdac2834e9f5537)): ?>
<?php $component = $__componentOriginal5272b2ca8b6e42e14bdac2834e9f5537; ?>
<?php unset($__componentOriginal5272b2ca8b6e42e14bdac2834e9f5537); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="activeTab === 'localization'" x-cloak>
                <div class="space-y-6">
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <?php echo e(__('settings.sections.regional_settings')); ?>

                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginald35510d91ed4b17b8d527b744ad2c37d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald35510d91ed4b17b8d527b744ad2c37d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.specialized.language-select','data' => ['name' => 'language_id','label' => __('settings.fields.language'),'value' => $tenant->language_id,'placeholder' => __('settings.placeholders.select_language'),'hint' => __('settings.hints.language'),'languages' => $languages,'xModel' => 'form.language_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.specialized.language-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'language_id','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.language')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->language_id),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.select_language')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.language')),'languages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($languages),'x-model' => 'form.language_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald35510d91ed4b17b8d527b744ad2c37d)): ?>
<?php $attributes = $__attributesOriginald35510d91ed4b17b8d527b744ad2c37d; ?>
<?php unset($__attributesOriginald35510d91ed4b17b8d527b744ad2c37d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald35510d91ed4b17b8d527b744ad2c37d)): ?>
<?php $component = $__componentOriginald35510d91ed4b17b8d527b744ad2c37d; ?>
<?php unset($__componentOriginald35510d91ed4b17b8d527b744ad2c37d); ?>
<?php endif; ?>
                            </div>



                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginala0c21be841693875876631bd4d643c2d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala0c21be841693875876631bd4d643c2d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.specialized.timezone-select','data' => ['name' => 'timezone_id','label' => __('settings.fields.timezone'),'value' => $tenant->timezone_id,'placeholder' => __('settings.placeholders.select_timezone'),'hint' => __('settings.hints.timezone'),'timezones' => $timezones,'xModel' => 'form.timezone_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.specialized.timezone-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'timezone_id','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.timezone')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->timezone_id),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.placeholders.select_timezone')),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.timezone')),'timezones' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($timezones),'x-model' => 'form.timezone_id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala0c21be841693875876631bd4d643c2d)): ?>
<?php $attributes = $__attributesOriginala0c21be841693875876631bd4d643c2d; ?>
<?php unset($__attributesOriginala0c21be841693875876631bd4d643c2d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala0c21be841693875876631bd4d643c2d)): ?>
<?php $component = $__componentOriginala0c21be841693875876631bd4d643c2d; ?>
<?php unset($__componentOriginala0c21be841693875876631bd4d643c2d); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <?php echo e(__('settings.sections.datetime_formats')); ?>

                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal4bb6b71f0e9770e6f13095b9f2042a3f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4bb6b71f0e9770e6f13095b9f2042a3f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.specialized.date-format-select','data' => ['name' => 'date_format','label' => __('settings.fields.date_format'),'value' => $tenant->date_format,'hint' => __('settings.hints.date_format'),'xModel' => 'form.date_format']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.specialized.date-format-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'date_format','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.date_format')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->date_format),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.date_format')),'x-model' => 'form.date_format']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4bb6b71f0e9770e6f13095b9f2042a3f)): ?>
<?php $attributes = $__attributesOriginal4bb6b71f0e9770e6f13095b9f2042a3f; ?>
<?php unset($__attributesOriginal4bb6b71f0e9770e6f13095b9f2042a3f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4bb6b71f0e9770e6f13095b9f2042a3f)): ?>
<?php $component = $__componentOriginal4bb6b71f0e9770e6f13095b9f2042a3f; ?>
<?php unset($__componentOriginal4bb6b71f0e9770e6f13095b9f2042a3f); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginal563ec0ee1c0edac4324e20541c03714b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal563ec0ee1c0edac4324e20541c03714b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.specialized.time-format-select','data' => ['name' => 'time_format','label' => __('settings.fields.time_format'),'value' => $tenant->time_format,'hint' => __('settings.hints.time_format'),'xModel' => 'form.time_format']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.specialized.time-format-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'time_format','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.fields.time_format')),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant->time_format),'hint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('settings.hints.time_format')),'x-model' => 'form.time_format']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal563ec0ee1c0edac4324e20541c03714b)): ?>
<?php $attributes = $__attributesOriginal563ec0ee1c0edac4324e20541c03714b; ?>
<?php unset($__attributesOriginal563ec0ee1c0edac4324e20541c03714b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal563ec0ee1c0edac4324e20541c03714b)): ?>
<?php $component = $__componentOriginal563ec0ee1c0edac4324e20541c03714b; ?>
<?php unset($__componentOriginal563ec0ee1c0edac4324e20541c03714b); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="activeTab === 'subscription'" x-cloak>
                <div class="space-y-6">
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">
                                    <?php echo e(__('settings.subscription.current_plan')); ?>

                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <dl class="space-y-1">
                                        <div class="flex items-center">
                                            <dt class="font-medium"><?php echo e(__('settings.subscription.plan')); ?>:</dt>
                                            <dd class="ml-2"><?php echo e($tenant->getPlanName()); ?></dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="font-medium"><?php echo e(__('settings.subscription.status')); ?>:</dt>
                                            <dd class="ml-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <?php echo e($tenant->getStatusName()); ?>

                                                </span>
                                            </dd>
                                        </div>
                                        <?php if($tenant->subscription_ends_at): ?>
                                            <div class="flex items-center">
                                                <dt class="font-medium"><?php echo e(__('settings.subscription.expires')); ?>:</dt>
                                                <dd class="ml-2"><?php echo dt($tenant->subscription_ends_at)->toDateTimeString(); ?></dd>
                                            </div>
                                        <?php endif; ?>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <?php echo e(__('settings.subscription.usage_statistics')); ?>

                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e(__('settings.subscription.users')); ?>

                                    </h4>
                                    <i class="fa-solid fa-users text-gray-400"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php echo e($tenant->current_users); ?>

                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            / <?php echo e($tenant->max_users); ?>

                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: <?php echo e(min($tenant->getUserUsagePercentage(), 100)); ?>%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($tenant->getUserUsagePercentage()); ?>% <?php echo e(__('settings.subscription.used')); ?>

                                    </p>
                                </div>
                            </div>

                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e(__('settings.subscription.storage')); ?>

                                    </h4>
                                    <i class="fa-solid fa-hard-drive text-gray-400"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php echo e(number_format($tenant->current_storage_mb / 1024, 1)); ?>

                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            / <?php echo e(number_format($tenant->max_storage_mb / 1024, 1)); ?> GB
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: <?php echo e(min($tenant->getStorageUsagePercentage(), 100)); ?>%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($tenant->getStorageUsagePercentage()); ?>% <?php echo e(__('settings.subscription.used')); ?>

                                    </p>
                                </div>
                            </div>

                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e(__('settings.subscription.events')); ?>

                                    </h4>
                                    <i class="fa-solid fa-calendar-days text-gray-400"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            <?php echo e($tenant->current_events); ?>

                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            / <?php echo e($tenant->max_events); ?>

                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                        <div class="bg-purple-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: <?php echo e(min($tenant->getEventUsagePercentage(), 100)); ?>%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($tenant->getEventUsagePercentage()); ?>% <?php echo e(__('settings.subscription.used')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    <span x-show="!hasChanges"><?php echo e(__('settings.hints.no_changes')); ?></span>
                    <span x-show="hasChanges"><?php echo e(__('settings.hints.save_changes')); ?></span>
                </p>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150"
                        :disabled="loading || !hasChanges"
                        :class="{ 'opacity-50 cursor-not-allowed': !hasChanges }">
                    <span x-show="!loading" class="inline-flex items-center">
                        <i class="fa-solid fa-save mr-2"></i>
                        <?php echo e(__('common.actions.save_changes')); ?>

                    </span>
                    <span x-show="loading" x-cloak class="inline-flex items-center">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                        <?php echo e(__('common.actions.saving')); ?>

                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function settingsForm() {
    return {
        activeTab: 'general',
        loading: false,
        hasChanges: false,
        originalForm: null,
        form: {
            // Basic Information
            name: <?php echo json_encode($tenant->name, 15, 512) ?>,
            legal_name: <?php echo json_encode($tenant->legal_name, 15, 512) ?>,
            
            // Contact Information
            email: <?php echo json_encode($tenant->email, 15, 512) ?>,
            phone: <?php echo json_encode($tenant->phone, 15, 512) ?>,
            website: <?php echo json_encode($tenant->website, 15, 512) ?>,
            
            // Address Information
            address_line_1: <?php echo json_encode($tenant->address_line_1, 15, 512) ?>,
            address_line_2: <?php echo json_encode($tenant->address_line_2, 15, 512) ?>,
            city: <?php echo json_encode($tenant->city, 15, 512) ?>,
            state: <?php echo json_encode($tenant->state, 15, 512) ?>,
            postal_code: <?php echo json_encode($tenant->postal_code, 15, 512) ?>,
            country_id: <?php echo json_encode($tenant->country_id, 15, 512) ?>,
            
            // Localization Settings
            language_id: <?php echo json_encode($tenant->language_id, 15, 512) ?>,
            timezone_id: <?php echo json_encode($tenant->timezone_id, 15, 512) ?>,
            date_format: <?php echo json_encode($tenant->date_format, 15, 512) ?>,
            time_format: <?php echo json_encode($tenant->time_format, 15, 512) ?>,
        },
        
        init() {
            // Meet2Be: Store original form data
            this.originalForm = JSON.stringify(this.form);
            
            // Watch for form changes
            this.$watch('form', () => {
                this.hasChanges = JSON.stringify(this.form) !== this.originalForm;
            }, { deep: true });
        },
        
        async submitForm() {
            if (!this.hasChanges) return;
            
            this.loading = true;
            
            try {
                const response = await fetch('<?php echo e(route('portal.setting.update', $tenant)); ?>', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Update original form data
                    this.originalForm = JSON.stringify(this.form);
                    this.hasChanges = false;
                    
                    // Show success notification
                    if (window.notify) {
                        window.notify('<?php echo e(__('common.success')); ?>', data.message || '<?php echo e(__('settings.messages.saved_successfully')); ?>', 'success');
                    }
                    
                    // If datetime settings changed, reload page to update all datetime displays
                    if (data.datetime_updated) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } else {
                    // Show error notification
                    if (window.notify) {
                        window.notify('<?php echo e(__('common.error')); ?>', data.message || '<?php echo e(__('settings.messages.save_failed')); ?>', 'error');
                    }
                    
                    // Handle validation errors
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                }
            } catch (error) {
                console.error('Settings update error:', error);
                if (window.notify) {
                    window.notify('<?php echo e(__('common.error')); ?>', '<?php echo e(__('settings.messages.save_failed')); ?>', 'error');
                }
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views/portal/setting/index.blade.php ENDPATH**/ ?>