import React, { useState, useEffect } from "react"
import AlternatingResult from "./AlternatingResult"
import ArticleResult from "./ArticleResult"
import axios from 'axios'

const Filter = ( data ) => {
    const [selectedFilterVals, setSelectedFilterVals] = useState([])
    const [posts, setPosts] = useState(data.posts)
    const [page, setPage] = useState(1)

    const updateFilterVals = (e, term_id) => {
        e.preventDefault()
        let shallowFilterVals = [...selectedFilterVals]
        if(shallowFilterVals.includes(term_id)) {
            shallowFilterVals = shallowFilterVals.splice(shallowFilterVals.indexOf(term_id), 1)
        } else {
            shallowFilterVals.push(term_id)
        }

        setSelectedFilterVals(shallowFilterVals)
    }

    const loadMorePosts = () => {
        setPage(page + 1)
    }

    const removeTerm = ( event, termId ) => {
        event.stopPropagation()
        let newSelectedFilterVals = [...selectedFilterVals],
            targetIndex = newSelectedFilterVals.indexOf(termId)
        newSelectedFilterVals.splice(targetIndex, 1)
        setSelectedFilterVals(newSelectedFilterVals)
    }

    useEffect(() => {
        if(!selectedFilterVals.length) return

        const queryString = selectedFilterVals.join(",")
        axios.get(`${data.restUrl}wps/v1/data?post_type=${data.postType}&${data.taxonomy}=${queryString}&page=${page}`)
            .then(res => {
                setPosts(res.data.posts)
            })
            .catch(err => {
                console.error(err)
            })
    }, [selectedFilterVals, page])

    return (
        <div className="w-full">
            <div className="filter-choices sm:min-h-[400px] mb-20 relative text-center text-white py-20 flex items-center">
                <div className="container mx-auto">
                    <div className="w-full flex justify-center items-center">
                        <h3 className="text-white">Filter</h3>
                        <button data-collapse-toggle="filter-items" type="button" className="filter-toggle inline-flex items-center p-2 justify-center text-sm ml-4 md:hidden" aria-controls="filter-items" aria-expanded="false">
                            <span className="sr-only">Open filter</span>
                            <svg className="w-[2.5rem] h-[2.5rem] open-toggle" xmlns="http://www.w3.org/2000/svg" width="40" height="41" viewBox="0 0 40 41" fill="none">
                                <path d="M6.66669 35.5929V23.9263" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.66669 17.2594V5.59277" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 35.5928V20.5928" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 13.9261V5.59277" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M33.3333 35.5926V27.2593" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M33.3333 20.5928V5.59277" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.66669 23.9263H11.6667" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 13.9263H25" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M28.3333 27.2593H38.3333" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg className="w-[2.5rem] h-[2.5rem] close-toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" fill="none">
                                <path d="M26.25 8.75L8.75 26.25" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.75 8.75L26.25 26.25" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                    <div id="filter-items" className="hidden inline-flex flex-wrap justify-start sm:justify-center py-20">
                        {data.terms.map((term, index) => {
                            let isActive = selectedFilterVals.includes(term.term_id)
                            return (
                                <button key={index} className={`filter-btn w-fit inline-flex items-center ${isActive ? 'active' : ''}`} type="button" onClick={(e) => updateFilterVals(e, term.term_id)}>
                                    <span className={`${isActive ? 'bg-accent' : 'bg-light'} self-stretch p-[0.375rem]`}><img className="object-contain" src={term.fields.svg_icon} alt="Term Icon" /></span>
                                    <span className="btn-inner">{term.name}</span>
                                    {isActive ? 
                                        <span className="remove-term" onClick={(event) => removeTerm(event, term.term_id)}>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                <path d="M7.89258 3.23987L2.89258 8.23987" stroke="#093642" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M2.89258 3.23987L7.89258 8.23987" stroke="#093642" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    : null}
                                </button>
                            )
                        })}
                    </div>
                </div>
                <div className="background bg-dark"></div>
                <div className="absolute decoration left-0 top-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="89" height="1066" viewBox="0 0 89 1066" fill="none">
                        <path d="M-202 1065.32L88.3704 282.471L-176.427 -2.45403e-05L-202 1065.32Z" fill="#B4D43D" fillOpacity="0.6"/>
                    </svg>
                </div>
            </div>
            <div className="w-full relative text-center mb-10">
                {posts.map((post, index) => {

                    if(data.style === "alternating") {
                        return <AlternatingResult key={index} index={index} post={post} />
                    } 

                    if(data.style === "article") {
                        return <ArticleResult key={index} index={index} post={post} />
                    }
                    return (
                        <div key={index}>
                            <h3>{post.post_title}</h3>
                        </div>
                    )
                })}
            </div>
            <div className="container flex justify-center">
                <button onClick={() => loadMorePosts()} className="btn btn-primary w-full">mehr {data.postType} zeigen</button>
            </div>
        </div>
    )
}

export default Filter