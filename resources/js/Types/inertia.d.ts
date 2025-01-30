import type { ErrorBag, Errors } from "@inertiajs/core";
import type { Role } from "./Role";
import type { User } from ".";

export type InertiaPageProps = {
    errors: Errors & ErrorBag;
    auth: {
        user: User;
    };
    laravelVersion: string;
    phpVersion: string;
    jetstream: {
        canCreateTeams: boolean;
        canManageTwoFactorAuthentication: boolean;
        canUpdatePassword: boolean;
        canUpdateProfileInformation: boolean;
        flash: Array;
        hasAccountDeletionFeatures: boolean;
        hasApiFeatures: boolean;
        hasEmailVerification: boolean;
        hasTeamFeatures: boolean;
        hasTermsAndPrivacyPolicyFeature: boolean;
        managesProfilePhotos: boolean;
    };
};
