export interface ButtonProps {
    title: string;
    action?: () => void;
    link?: string;
    disabled?: boolean;
    loading?: boolean;
}