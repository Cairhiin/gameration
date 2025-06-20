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
    median_rating: number;
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
    rating?: number;
    rating_count: number;
    user_ratings?: Rating[];
};

export type Book = {
    id: string;
    title: string;
    description: string;
    image: string;
    created_at?: string;
    updated_at?: string;
    released_at?: string;
    user_id: string;
    authors: Person[];
    narrators: Person[];
    publisher: Publisher;
    genres: Genre[];
    user_ratings?: Rating[];
    rating?: number;
    rating_count: number;
    avg_rating: number;
    median_rating: number;
    user?: User;
    published_at?: string;
    type: "physical" | "audiobook" | "ebook";
    pages: number;
    time: string;
    series_id?: string;
    series_book_number?: number;
    ISBN: string;
    series?: Series;
    pivot?: {
        book_id: string;
        user_id: string;
        rating: number;
    };
    reviews?: Review[];
    reviews_count?: number;
};

export type Series = {
    id: string;
    title: string;
    description: string;
    authors: Person[];
    created_at?: string;
    updated_at?: string;
    user_id: string;
    user?: User;
    books?: Book[];
};

export type Person = {
    id: string;
    name: string;
    description: string;
    created_at?: string;
    updated_at?: string;
    user_id: string;
    image?: File;
    type: "author" | "narrator";
    books?: Book[];
    series?: Series[];
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

export type Review = {
    id: number;
    game_id: string;
    user_id: string;
    rating_id: string;
    content: string;
    rating: number;
    created_at?: string;
    updated_at?: string;
    user?: User;
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
    games_rated_count?: number;
    books_rated_count?: number;
};

export type DashboardData = {
    averageGameRating?: number;
    averageBookRating?: number;
    total_friends?: number;
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

export type Achievement = {
    id: string;
    title: string;
    description: string;
    points: number;
    image: string;
    unlocked_at?: string;
    isCompleted: boolean;
    created_at?: string;
    updated_at?: string;
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
