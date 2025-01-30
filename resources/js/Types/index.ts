export type Developer = {
    avg_rating: number;
    created_at?: string;
    games_count: number;
    id: string;
    name: string;
    user_id: string;
    updated_at?: string;
    year: string;
    city: string;
    country: string;
    games: Game[];
};

export type Publisher = {
    avg_rating: number;
    created_at?: string;
    games_count: number;
    id: string;
    name: string;
    user_id: string;
    updated_at?: string;
    year: string;
    city: string;
    country: string;
    games: Game[];
};

export type Genre = {
    created_at?: string;
    games_count: number;
    id: string;
    name: string;
    updated_at?: string;
    pivot: {
        genre_id: number;
        game_id: number;
    };
    avg_rating: number;
    user_id: string;
};

export type Game = {
    avg_rating: number;
    created_at?: string;
    description: string;
    developer: Developer;
    genres: Genre[];
    id: string;
    image: string;
    name: string;
    publisher: Publisher;
    released_at?: string;
    updated_at?: string;
    rating_count: number;
    user_ratings?: Rating[];
};

export type Rating = {
    rating: number;
    game_id: string;
    game: Game;
    user?: User;
    user_id: string;
    created_at?: string;
    updated_at?: string;
};

export type User = {
    name: string;
    email: string;
    id: string;
    username: string;
    roles: Role[];
    profile_photo_url?: string;
    profile_photo_path?: string;
    games_count?: number;
    ratings_count?: number;
    created_at?: string;
    updated_at?: string;
    friends_count?: number;
    friends?: User[];
    email_verified_at: string;
    pivot?: {
        user_id: string;
        friend_id: string;
        accepted: boolean;
    };
    two_factor_enabled: boolean;
};

export type Message = {
    id: string;
    sender_id: string;
    receiver_id: string;
    subject: string;
    body: string;
    read: boolean;
    created_at?: string;
    updated_at?: string;
    archived: boolean;
    sender?: User;
    receiver?: User;
};

export type MessageList = {
    inbox: Data<Message>;
    sent: Data<Message>;
};

export type Link = {
    url?: string;
    label: string;
    active: boolean;
};

export type Role = {
    name: string;
    id: string;
    pivot: {
        user_id: string;
        role_id: string;
    };
    created_at?: string;
    updated_at?: string;
};

export type Data<T> = {
    current_page: number;
    data: Array<T>;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Array<Link>;
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
};

export type Session = {
    id: string;
    ip_address: string;
    agent: {
        is_desktop: boolean;
        platform: string;
        browser: string;
    };
    last_active: string;
    is_current_device: boolean;
};
