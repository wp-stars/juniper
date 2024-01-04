import React, { useState } from "react"

const AlternatingResult = ({ index, post }) => {
    return (
        <div className={`container mx-auto min-h-[600px] mb-52 grid grid-cols-1 sm:grid-cols-2 ${index % 2 === 0 ? 'even' : 'odd'}`}>
            <div className={`order-1 ${index % 2 === 0 ? 'sm:order-1' : 'sm:order-2'}`}>
                <div className="min-h-[650px] sm:min-h-[unset]">
                    <div className="teaser-image absolute z-0">
                        <div className="absolute decoration"></div>
                        <img className="absolute" src={post.fields.teaser_image} />
                    </div>
                    <div className="showcase-image z-10 relative">
                        <img className="max-w-[300px]" alt="Showcase Image" src={post.fields.showcase_image} />
                    </div>
                </div>
            </div>
            <div className={`order-2 ${index % 2 === 0 ? 'sm:order-2' : 'sm:order-1'} flex flex-col justify-center text-left`}>
                <h3>{post.post_title} // {post.fields.year}</h3>
                <p className="mb-10">
                    {post.terms.map((term, index) => (
                            <React.Fragment key={index}>
                                {index < post.terms.length && index > 0 && ' // '}
                                <span dangerouslySetInnerHTML={{ __html: `${term.name}` }}></span>
                            </React.Fragment>
                        )
                    )}
                </p>
                <div className="mb-20">
                    {post.excerpt}
                </div>
                <a className="btn btn-underline" href={post.link}>
                    Mehr über {post.post_title}
                </a>
            </div>
        </div>
    )
}

export default AlternatingResult
