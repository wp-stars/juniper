import React, { useState, useEffect } from "react"
import AlternatingResult from "./AlternatingResult"
import ArticleResult from "./ArticleResult"
import axios from 'axios'
import { useMediaQuery } from 'react-responsive'

const Filter = ( data ) => {
    console.log(data);
    const [selectedFilterVals, setSelectedFilterVals] = useState([])
    const [posts, setPosts] = useState([])
    const [page, setPage] = useState(1)
    const [loadingMore, setLoadingMore] = useState(false)
    const [maxPages, setMaxPages] = useState(data.maxNumPages)
    const [showFilterItems, setShowFilterItems] = useState(false)
    const isMobile = useMediaQuery({ query: `(max-width: 640px)` })
    

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
        setLoadingMore(true)
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
        let queryString = `?post_type=${data.postType}`
        if(data.taxonomy) {
            queryString += `&taxonomy=${data.taxonomy}`

            let taxQueryString = selectedFilterVals.join(",")
            queryString += `&terms=${taxQueryString}`
        }

        queryString += `&page=${page}`
        axios.get(`${data.restUrl}wps/v1/data${queryString}`)
            .then(res => {
                if(page > 1) {
                    setPosts([...posts, ...res.data.posts])
                } else {
                    setPosts(res.data.posts)
                }
                setMaxPages(res.data.maxNumPages)
                setLoadingMore(false)
            })
            .catch(err => {
                console.error(err)
            })
    }, [selectedFilterVals, page])
    
    const toggleFilterOpen = (e) => {
        e.preventDefault()
        console.log('toggling filter')
        setShowFilterItems(!showFilterItems)
    }

    useEffect(() => {
        // window.addEventListener("resize", handleResize)
        if(!isMobile) setShowFilterItems(true)
    }, [isMobile])
  

    return (
        <div className="w-full">
            <div className="filter-choices sm:min-h-[400px] mb-20 relative text-center text-white py-20 flex items-center">
                <div className="container mx-auto">
                    {isMobile ? 
                        <div className="w-full flex justify-center items-center">
                            <h3 className="text-white">Filter</h3>
                            <button 
                                type="button" 
                                className={`filter-toggle inline-flex items-center p-2 justify-center text-sm ml-4 ${showFilterItems ? 'open' : 'closed'}`}
                                onClick={toggleFilterOpen}
                            >
                                <span className="sr-only">Open filter</span>
                                <svg className="w-[2.5rem] h-[2.5rem] open-toggle" xmlns="http://www.w3.org/2000/svg" width="40" height="41" viewBox="0 0 40 41" fill="none">
                                    <path d="M6.66669 35.5929V23.9263" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M6.66669 17.2594V5.59277" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M20 35.5928V20.5928" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M20 13.9261V5.59277" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M33.3333 35.5926V27.2593" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M33.3333 20.5928V5.59277" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M1.66669 23.9263H11.6667" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M15 13.9263H25" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M28.3333 27.2593H38.3333" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                </svg>
                                <svg className="w-[2.5rem] h-[2.5rem] close-toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" fill="none">
                                    <path d="M26.25 8.75L8.75 26.25" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                    <path d="M8.75 8.75L26.25 26.25" stroke="#F9F9F9" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    : null}
                    {showFilterItems ? 
                        <div id="filter-items" className="inline-flex flex-wrap justify-start sm:justify-center py-20">
                            {data.terms.map((term, index) => {
                                if(term.slug === "uncategorized") return null
                                
                                let isActive = selectedFilterVals.includes(term.term_id)
                                return (
                                    <button key={index} className={`filter-btn w-fit inline-flex items-center ${isActive ? 'active' : ''}`} type="button" onClick={(e) => updateFilterVals(e, term.term_id)}>
                                        <span className={`${isActive ? 'bg-accent' : 'bg-light'} self-stretch p-[0.375rem]`}><img className="object-contain" src={term.fields.svg_icon} alt="Term Icon" /></span>
                                        <span className="btn-inner">{term.name}</span>
                                        {isActive ? 
                                            <span className="remove-term" onClick={(event) => removeTerm(event, term.term_id)}>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                    <path d="M7.89258 3.23987L2.89258 8.23987" stroke="#093642" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                    <path d="M2.89258 3.23987L7.89258 8.23987" stroke="#093642" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                </svg>
                                            </span>
                                        : null}
                                    </button>
                                )
                            })}
                        </div>
                    : null}
                    
                </div>
                <div className="background bg-dark"></div>
                <div className="absolute decoration left-0 top-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="89" height="1066" viewBox="0 0 89 1066" fill="none">
                        <path d="M-202 1065.32L88.3704 282.471L-176.427 -2.45403e-05L-202 1065.32Z" fill="#B4D43D" fillOpacity="0.6"/>
                    </svg>
                </div>
            </div>
            <div className="w-full relative text-center mb-10">
            
                {posts.length ? 
                    <>
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
                    </>
                : 
                    <div className="w-full text-center">
                        keine Ergebnisse.
                    </div>
                }
            </div>
                {loadingMore ? 
                    <div className="container flex justify-center">
                        <p>Loading...</p>
                    </div>
                : 
                    <div className="container flex justify-center">
                        {page < maxPages ? 
                            <button onClick={() => loadMorePosts()} className="btn btn-primary w-full">Mehr {data.postName} zeigen</button>
                        : null}
                    </div>
                }
        </div>
    )
}

export default Filter