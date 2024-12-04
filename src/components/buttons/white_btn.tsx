'use client'

import React from 'react';
import type { ButtonProps } from '@/types/layout';
import './CircularBtn.css';

export default function WhiteBtn({title, action, link}: ButtonProps) {
  const button = () => {
    if (link) {
      window.location.href = link;
    } else if (action) {
      action();
    } else {
      console.log('No action');
    }
  };

  return (
    <>
      <button
        onClick={button}
        className="bg-white relative inline-block aspect-square h-[52px] min-w-[122px] items-center justify-center rounded-full border border-gray-600"
      >
        <span className="z-10" />
        <span className="absolute left-1/2 top-1/2 z-20 -translate-x-1/2 -translate-y-1/2 justify-self-center text-black">
          {title}
        </span>
      </button>
    </>
  );
}