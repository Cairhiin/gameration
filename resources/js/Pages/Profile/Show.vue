<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import type { PropType } from 'vue';
import type { Session } from '@/Types';
import { usePage } from '@inertiajs/vue3';
import type { InertiaPageProps } from '@/Types/inertia';

const page = usePage<InertiaPageProps>();

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Object as PropType<Session[]>,
});
</script>

<template>
    <AppLayout title="Profile">
        <div class="backdrop-blur-sm">
            <div>
                <section v-if="page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="page.props.auth.user" />

                    <SectionBorder />
                </section>

                <section v-if="page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm class="mt-10 sm:mt-0" />

                    <SectionBorder />
                </section>

                <section v-if="page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-10 sm:mt-0" />

                    <SectionBorder />
                </section>

                <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

                <template v-if="page.props.jetstream.hasAccountDeletionFeatures">
                    <SectionBorder />

                    <DeleteUserForm class="mt-10 sm:mt-0" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
