import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;


/**
 * Chat and Message types
 */
export type Message = {
    id: number;
    chat_id: number;
    content: string;
    user_or_model: 1 | 2;
    created_at: string;
    updated_at: string;
}
export type Chat = {
    id: number;
    user_id: number;
    name?: stirng;
    created_at: string;
    updated_at: string;
    messages: Message[];
}
export interface ChatPageProps extends AppPageProps {
    chat: Chat;
}
export interface ChatsPageProps extends AppPageProps {
    chats: Chat[];
}