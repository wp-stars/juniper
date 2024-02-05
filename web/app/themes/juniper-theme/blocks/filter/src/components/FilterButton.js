import React, { useState } from 'react'

const FilterButton = ({ term, selectedFilterVals, setSelectedFilterVals }) => {
    if (term.slug === "uncategorized") return null

    const [isActive, setIsActive] = useState(selectedFilterVals.includes(term.term_id))

    const handleKeyPress = (e, term) => {
        if (e.key === 'Enter') {
            e.preventDefault()
            updateFilterVals(e, term.term_id)
        }
    }

    const updateFilterVals = (e, term_id) => {
        e.preventDefault()
        let shallowFilterVals = [...selectedFilterVals]
        if(shallowFilterVals.includes(term_id)) {
            shallowFilterVals.splice(shallowFilterVals.indexOf(term_id), 1)
            setIsActive(false)
        } else {
            shallowFilterVals.push(term_id)
            setIsActive(true)
        }
        setSelectedFilterVals(shallowFilterVals)
    }

    const handleClick = (e, term) => {
        updateFilterVals(e, term.term_id)
    }

    return (
        <button
            className={`filter-btn w-fit inline-flex items-center ${isActive ? 'active' : ''}`}
            type="button"
            onClick={(e) => handleClick(e, term)}
            onKeyDown={(e) => handleKeyPress(e, term)}
            tabIndex={0}
            onFocus={(e) => handleKeyPress(e, term)} // Ensure handleKeyPress is called on focus
        >
            <span className={`${isActive ? 'bg-accent' : 'bg-light'} self-stretch p-[0.375rem]`}><img className="object-contain" src={term.fields.svg_icon} alt="Term Icon" /></span>
            <span className="btn-inner">{term.name}</span>
            {isActive ? 
                <span className="remove-term" onClick={(event) => updateFilterVals(event, term.term_id)}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                        <path d="M7.89258 3.23987L2.89258 8.23987" stroke="#093642" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                        <path d="M2.89258 3.23987L7.89258 8.23987" stroke="#093642" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                    </svg>
                </span>
            : null}
        </button>
    )
}

export default FilterButton
